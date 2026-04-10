<?php

namespace App\Models\Forms;

use App\Concerns\HasRelatables;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[Fillable([
    'title',
    'slug',
    'description',
    'form_type_id',
    'is_published',
    'is_active',
    'requires_login',
    'allow_multiple_responses',
    'max_responses',
    'collect_email',
    'show_progress_bar',
    'shuffle_sections',
    'available_from',
    'available_until',
    'confirmation_message',
    'redirect_url',
    'is_quiz_mode',
    'time_limit_minutes',
    'max_attempts',
    'passing_score',
    'randomize_questions',
    'randomize_options',
    'show_results_to_respondent',
    'show_correct_answers',
    'show_feedback_after',
    'user_id',
])]
class Form extends Model
{
    use HasFactory, HasSlug, LogsActivity, SoftDeletes, HasRelatables;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_active' => 'boolean',
            'requires_login' => 'boolean',
            'allow_multiple_responses' => 'boolean',
            'collect_email' => 'boolean',
            'show_progress_bar' => 'boolean',
            'shuffle_sections' => 'boolean',
            'available_from' => 'datetime:Y-m-d H:i:s',
            'available_until' => 'datetime:Y-m-d H:i:s',
            'is_quiz_mode' => 'boolean',
            'passing_score' => 'float',
            'randomize_questions' => 'boolean',
            'randomize_options' => 'boolean',
            'show_correct_answers' => 'boolean',
            'show_feedback_after' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(FormType::class, 'form_type_id');
    }

    public function sections()
    {
        return $this->hasMany(FormSection::class)->orderBy('order');
    }

    public function questions()
    {
        return $this->hasMany(FormQuestion::class)->orderBy('order');
    }

    public function logicRules()
    {
        return $this->hasMany(FormLogicRule::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(FormResponse::class);
    }

    public function attachments()
    {
        return $this->hasMany(FormAttachment::class)->orderBy('order');
    }
}
