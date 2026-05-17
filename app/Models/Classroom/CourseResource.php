<?php

namespace App\Models\Classroom;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'url', 'order', 'resource_type_id'])]
class CourseResource extends Model
{
    public function resourceable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }
}
