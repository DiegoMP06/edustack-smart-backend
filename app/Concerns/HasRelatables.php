<?php

namespace App\Concerns;

use App\Enums\Relations\RelatableContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasRelatables
{
    protected function getRelatableTable(): string
    {
        if (property_exists($this, 'relatableTable')) {
            return $this->relatableTable;
        }

        $base = class_basename(static::class);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $base)) . '_relatables';
    }

    protected function getRelatableForeignKey(): string
    {
        if (property_exists($this, 'relatableForeignKey')) {
            return $this->relatableForeignKey;
        }

        $base = class_basename(static::class);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $base)) . '_id';
    }

    public function relatables(string $relatedClass): MorphToMany
    {
        return $this->morphedByMany(
            $relatedClass,
            'relatable',
            $this->getRelatableTable(),
            $this->getRelatableForeignKey(),
        )->withPivot(['context', 'order'])
            ->orderByPivot('order');
    }

    public function relatedPosts(): MorphToMany
    {
        return $this->relatables(\App\Models\Blog\Post::class);
    }

    public function relatedProjects(): MorphToMany
    {
        return $this->relatables(\App\Models\Projects\Project::class);
    }

    public function relatedEvents(): MorphToMany
    {
        return $this->relatables(\App\Models\Events\Event::class);
    }

    public function relatedEventActivities(): MorphToMany
    {
        return $this->relatables(\App\Models\Events\EventActivity::class);
    }

    public function relatedCourses(): MorphToMany
    {
        return $this->relatables(\App\Models\Classroom\Course::class);
    }

    public function relatedCourseLessons(): MorphToMany
    {
        return $this->relatables(\App\Models\Classroom\CourseLesson::class);
    }

    public function relatedForms(): MorphToMany
    {
        return $this->relatables(\App\Models\Forms\Form::class);
    }

    public function relatedAssignments(): MorphToMany
    {
        return $this->relatables(\App\Models\Classroom\Assignment::class);
    }

    public function attachRelatable(
        Model $target,
        RelatableContext $context = RelatableContext::RELATED,
        int $order = 0,
    ): void {
        $this->relatables($target::class)->syncWithoutDetaching([
            $target->getKey() => [
                'context' => $context->value,
                'order' => $order,
            ],
        ]);
    }
    
    public function detachRelatable(
        Model $target,
        ?RelatableContext $context = null,
    ): void {
        if ($context === null) {
            $this->relatables($target::class)->detach($target->getKey());
            return;
        }

        $this->relatables($target::class)
            ->wherePivot('context', $context->value)
            ->detach($target->getKey());
    }
}
