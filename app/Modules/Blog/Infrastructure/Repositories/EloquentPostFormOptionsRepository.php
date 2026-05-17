<?php

namespace App\Modules\Blog\Infrastructure\Repositories;

use App\Models\Blog\PostCategory;
use App\Models\Blog\PostType;
use App\Modules\Blog\Domain\Contracts\PostFormOptionsRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentPostFormOptionsRepository implements PostFormOptionsRepository
{
    public function getTypes(): Collection
    {
        return PostType::orderBy('order')->get();
    }

    public function getCategories(): Collection
    {
        return PostCategory::orderBy('order')->get();
    }
}
