<?php

namespace App\Models\Projects;

use App\Enums\Projects\RolesOfProjectCollaborators;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['project_id', 'user_id', 'role'])]
class ProjectCollaborator extends Model
{
    use HasFactory, LogsActivity;

    protected function casts(): array
    {
        return [
            'role' => RolesOfProjectCollaborators::class,
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
