<?php

declare(strict_types=1);

namespace Ses\Model;

use DateTimeImmutable;
use Ses\Support\Row;

/** A blog post (table `blogs`). */
final readonly class Article
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public string $category,
        public string $author,
        public ?DateTimeImmutable $createdAt,
        public ?string $image,
        public bool $visible,
        public int $viewCount,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: Row::int($row, 'id'),
            title: Row::string($row, 'title'),
            content: Row::string($row, 'content'),
            category: Row::string($row, 'category'),
            author: Row::string($row, 'author'),
            createdAt: Row::nullableDate($row, 'created_at'),
            image: Row::nullableString($row, 'image'),
            visible: Row::bool($row, 'visible'),
            viewCount: Row::nullableInt($row, 'view_count') ?? 0,
        );
    }

    public function withViewCount(int $viewCount): self
    {
        return new self(
            $this->id,
            $this->title,
            $this->content,
            $this->category,
            $this->author,
            $this->createdAt,
            $this->image,
            $this->visible,
            $viewCount,
        );
    }
}
