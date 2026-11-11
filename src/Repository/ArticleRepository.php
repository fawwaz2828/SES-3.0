<?php

declare(strict_types=1);

namespace Ses\Repository;

use Ses\Database;
use Ses\Dto\ArticleInput;
use Ses\Http\UploadedImage;
use Ses\Model\Article;
use Ses\Support\Row;

/** Blog posts (table `blogs`). */
final readonly class ArticleRepository
{
    public function __construct(private Database $db) {}

    /**
     * Visible posts in the order chosen by the admin.
     *
     * @return list<Article>
     */
    public function featured(int $limit): array
    {
        return $this->many(
            'SELECT * FROM blogs WHERE visible = 1 ORDER BY display_order ASC, created_at DESC LIMIT ?',
            [$limit],
        );
    }

    /**
     * @return list<Article>
     */
    public function popular(int $limit): array
    {
        return $this->many('SELECT * FROM blogs WHERE visible = 1 ORDER BY view_count DESC LIMIT ?', [$limit]);
    }

    /**
     * Visible posts, newest first, filtered by search text and/or category.
     *
     * @return list<Article>
     */
    public function search(string $text = '', ?string $category = null): array
    {
        $sql = 'SELECT * FROM blogs WHERE visible = 1';
        $params = [];
        if ($text !== '') {
            $sql .= ' AND (title LIKE ? OR content LIKE ?)';
            $params[] = '%' . $text . '%';
            $params[] = '%' . $text . '%';
        }
        if ($category !== null) {
            $sql .= ' AND category = ?';
            $params[] = $category;
        }

        return $this->many($sql . ' ORDER BY created_at DESC', $params);
    }

    /**
     * Categories of visible posts.
     *
     * @return list<string>
     */
    public function categories(): array
    {
        return $this->categoryList('SELECT DISTINCT category FROM blogs WHERE visible = 1 ORDER BY category');
    }

    /**
     * Categories of all posts, including hidden ones (admin).
     *
     * @return list<string>
     */
    public function allCategories(): array
    {
        return $this->categoryList('SELECT DISTINCT category FROM blogs ORDER BY category');
    }

    public function findVisible(int $id): ?Article
    {
        $row = $this->db->one('SELECT * FROM blogs WHERE id = ? AND visible = 1', [$id]);

        return $row === null ? null : Article::fromRow($row);
    }

    /**
     * @return list<Article>
     */
    public function related(Article $article, int $limit): array
    {
        return $this->many(
            'SELECT * FROM blogs WHERE id != ? AND category = ? AND visible = 1 ORDER BY created_at DESC LIMIT ?',
            [$article->id, $article->category, $limit],
        );
    }

    /**
     * Counts at most one view per IP address per day.
     *
     * @return bool true when a new view was counted
     */
    public function recordView(int $articleId, string $ip): bool
    {
        $seen = $this->db->int(
            'SELECT COUNT(*) FROM blog_views WHERE blog_id = ? AND user_ip = ? AND view_time > NOW() - INTERVAL 1 DAY',
            [$articleId, $ip],
        );
        if ($seen > 0) {
            return false;
        }

        $this->db->execute('UPDATE blogs SET view_count = view_count + 1 WHERE id = ?', [$articleId]);
        $this->db->execute('INSERT INTO blog_views (blog_id, user_ip) VALUES (?, ?)', [$articleId, $ip]);

        return true;
    }

    /**
     * All posts including hidden ones, newest first (admin).
     *
     * @return list<Article>
     */
    public function all(): array
    {
        return $this->many('SELECT * FROM blogs ORDER BY created_at DESC');
    }

    public function count(): int
    {
        return $this->db->int('SELECT COUNT(*) FROM blogs');
    }

    public function create(ArticleInput $input, UploadedImage $image, string $author): void
    {
        $order = $this->db->int('SELECT COALESCE(MAX(display_order), 0) + 1 FROM blogs');
        $this->db->execute(
            'INSERT INTO blogs (title, content, category, image, visible, display_order, author) VALUES (?, ?, ?, ?, 1, ?, ?)',
            [$input->title, $input->content, $input->category, $image->bytes, $order, $author],
        );
    }

    public function toggleVisibility(int $id): void
    {
        $this->db->execute('UPDATE blogs SET visible = 1 - visible WHERE id = ?', [$id]);
    }

    public function delete(int $id): void
    {
        $this->db->execute('DELETE FROM blog_views WHERE blog_id = ?', [$id]);
        $this->db->execute('DELETE FROM blogs WHERE id = ?', [$id]);
    }

    /**
     * @param list<scalar|null> $params
     * @return list<Article>
     */
    private function many(string $sql, array $params = []): array
    {
        return array_map(Article::fromRow(...), $this->db->all($sql, $params));
    }

    /**
     * @return list<string>
     */
    private function categoryList(string $sql): array
    {
        return array_map(static fn(array $row): string => Row::string($row, 'category'), $this->db->all($sql));
    }
}
