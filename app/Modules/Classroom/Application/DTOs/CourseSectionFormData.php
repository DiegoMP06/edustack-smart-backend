<?php

namespace App\Modules\Classroom\Application\DTOs;

use Spatie\LaravelData\Data;

class CourseSectionFormData extends Data
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $order,
        public bool $is_published,
    ) {}
}
