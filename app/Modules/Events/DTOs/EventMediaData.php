<?php

namespace App\Modules\Events\DTOs;

readonly class EventMediaData
{
    /**
     * @param  array<int, string>  $images
     */
    public function __construct(
        public array $images,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            images: array_values($data['images'] ?? []),
        );
    }
}
