<?php

namespace App\Modules\Forms\DTOs;

readonly class FormData
{
    public function __construct(
        // Add strongly typed DTO properties here.
    ) {}

    public static function fromArray(array $data): self
    {
        return new self;
        // Map array values to constructor arguments.
    }

    public static function fromModel(mixed $model): self
    {
        return new self;
        // Map model attributes to constructor arguments.
    }

    public function toArray(): array
    {
        return [
            // Return the DTO payload as an array.
        ];
    }
}
