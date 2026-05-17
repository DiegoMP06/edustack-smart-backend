<?php

namespace App\Modules\Shared\Concerns;

use Carbon\CarbonImmutable;

trait NormalizesDateTimeFields
{
    public function normalizeDateTimeFields(array $fields): void
    {
        $normalized = [];

        foreach ($fields as $field) {
            $value = $this->input($field);
            if ($value) {
                $normalized[$field] = CarbonImmutable::parse($value);
            }
        }

        $this->merge($normalized);
    }
}
