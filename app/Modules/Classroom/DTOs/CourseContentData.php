<?php

namespace App\Modules\Classroom\DTOs;

readonly class CourseContentData
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
