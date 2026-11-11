<?php

declare(strict_types=1);

namespace Ses\Repository;

use Ses\Database;
use Ses\Dto\ProductInput;
use Ses\Http\UploadedImage;
use Ses\Model\Product;
use Ses\Support\Row;

final readonly class ProductRepository
{
    public function __construct(private Database $db) {}

    /**
     * @return list<string>
     */
    public function categories(): array
    {
        $rows = $this->db->all('SELECT DISTINCT category FROM products ORDER BY category');

        return array_map(static fn(array $row): string => Row::string($row, 'category'), $rows);
    }

    /**
     * Newest first, optionally filtered by category.
     *
     * @return list<Product>
     */
    public function latest(?string $category = null, ?int $limit = null): array
    {
        $sql = 'SELECT * FROM products';
        $params = [];
        if ($category !== null) {
            $sql .= ' WHERE category = ?';
            $params[] = $category;
        }
        $sql .= ' ORDER BY created_at DESC';
        if ($limit !== null) {
            $sql .= ' LIMIT ?';
            $params[] = $limit;
        }

        return array_map(Product::fromRow(...), $this->db->all($sql, $params));
    }

    public function find(int $id): ?Product
    {
        $row = $this->db->one('SELECT * FROM products WHERE id = ?', [$id]);

        return $row === null ? null : Product::fromRow($row);
    }

    /**
     * Other products from the same category.
     *
     * @return list<Product>
     */
    public function related(Product $product, int $limit): array
    {
        $rows = $this->db->all(
            'SELECT * FROM products WHERE category = ? AND id != ? ORDER BY RAND() LIMIT ?',
            [$product->category, $product->id, $limit],
        );

        return array_map(Product::fromRow(...), $rows);
    }

    public function count(): int
    {
        return $this->db->int('SELECT COUNT(*) FROM products');
    }

    public function create(ProductInput $input, UploadedImage $image): void
    {
        $this->db->execute(
            'INSERT INTO products (name, category, price, description, image) VALUES (?, ?, ?, ?, ?)',
            [$input->name, $input->category, $input->price, $input->description, $image->bytes],
        );
    }

    public function delete(int $id): void
    {
        $this->db->execute('DELETE FROM products WHERE id = ?', [$id]);
    }
}
