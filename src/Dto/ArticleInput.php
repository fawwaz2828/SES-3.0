<?php

declare(strict_types=1);

namespace Ses\Dto;

use Ses\Support\Input;

/** Data from the admin "New post" form. */
final readonly class ArticleInput
{
    public function __construct(
        public string $title,
        public string $category,
        public string $content,
    ) {}

    /**
     * @param array<mixed> $input
     */
    public static function fromArray(array $input): self
    {
        return new self(
            title: Input::string($input, 'title', 255),
            category: Input::string($input, 'category', 50),
            content: Input::string($input, 'content'),
        );
    }

    /**
     * @return list<string>
     */
    public function errors(): array
    {
        return $this->title === '' || $this->category === '' || $this->content === ''
            ? ['Title, content and category are required.']
            : [];
    }
}
