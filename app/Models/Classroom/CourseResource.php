<?php

namespace App\Models\Classroom;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'url', 'order', 'resource_type_id'])]
class CourseResource extends Model
{
    public function resourceable()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }
}
