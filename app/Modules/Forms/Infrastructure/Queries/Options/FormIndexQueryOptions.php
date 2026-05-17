<?php

namespace App\Modules\Forms\Infrastructure\Queries\Options;

use App\Modules\Shared\Domain\Contracts\QueryOptionsContract;
use Spatie\QueryBuilder\AllowedFilter;

class FormIndexQueryOptions implements QueryOptionsContract
{
    public static function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::partial('title'),
            AllowedFilter::exact('is_published'),
            AllowedFilter::exact('is_active'),
            AllowedFilter::exact('form_type_id'),
        ];
    }

    public static function allowedSorts(): array
    {
        return [
            'id',
            'title',
            'created_at',
            'updated_at',
        ];
    }

    public static function defaultSort(): string
    {
        return '-created_at';
    }

    public static function allowedIncludes(): array
    {
        return [
            'type',
            'sections',
        ];
    }
}
