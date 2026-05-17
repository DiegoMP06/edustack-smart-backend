<?php

namespace App\Modules\Classroom\Providers;

use App\Modules\Classroom\Domain\Contracts\AssignmentSubmissionWriteRepository;
use App\Modules\Classroom\Domain\Contracts\CourseFormOptionsRepository;
use App\Modules\Classroom\Domain\Contracts\CourseReadRepository;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;
use App\Modules\Classroom\Infrastructure\Repositories\EloquentAssignmentSubmissionWriteRepository;
use App\Modules\Classroom\Infrastructure\Repositories\EloquentCourseFormOptionsRepository;
use App\Modules\Classroom\Infrastructure\Repositories\EloquentCourseReadRepository;
use App\Modules\Classroom\Infrastructure\Repositories\EloquentCourseWriteRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ClassroomProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerContracts();
    }

    public function boot(): void
    {
        $this->registerRoutes();
    }

    protected function registerContracts(): void
    {
        $this->app->bind(CourseReadRepository::class, EloquentCourseReadRepository::class);
        $this->app->bind(CourseWriteRepository::class, EloquentCourseWriteRepository::class);
        $this->app->bind(CourseFormOptionsRepository::class, EloquentCourseFormOptionsRepository::class);
        $this->app->bind(AssignmentSubmissionWriteRepository::class, EloquentAssignmentSubmissionWriteRepository::class);
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'verified', 'active', 'role:student|admin|member|teacher'])
            ->prefix('classroom')
            ->name('classroom.')
            ->group(base_path('app/Modules/Classroom/routes/classroom.php'));
    }
}
