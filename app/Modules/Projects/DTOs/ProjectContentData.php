<?php

namespace App\Modules\Projects\DTOs;

readonly class ProjectContentData
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
