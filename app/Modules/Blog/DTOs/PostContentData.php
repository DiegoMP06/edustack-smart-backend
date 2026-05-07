<?php

namespace App\Modules\Blog\DTOs;

readonly class PostContentData
{
    public function __construct(
        public array $content,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            content: (array) ($data['content'] ?? []),
        );
    }
}
