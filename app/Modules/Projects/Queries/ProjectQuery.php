<?php

namespace App\Modules\Projects\Queries;

use App\Models\Projects\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectQuery
{
    /**
     * Build the base query builder instance.
     */
    public static function build(): QueryBuilder
    {
        return QueryBuilder::for(Project::class)
            ->allowedFilters([
                // Add allowed filters.
            ])
            ->allowedSorts([
                // Add allowed sorts.
            ])
            ->allowedIncludes([
                // Add allowed includes.
            ]);
    }

    public static function get(): Collection
    {
        return self::build()->get();
    }

    public static function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return self::build()->paginate($perPage);
    }

    public static function count(): int
    {
        return self::build()->count();
    }
}
