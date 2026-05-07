<?php

namespace App\Modules\Forms\DTOs;

readonly class FormContentData
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
