<?php

namespace App\Modules\Events\DTOs;

readonly class EventContentData
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
