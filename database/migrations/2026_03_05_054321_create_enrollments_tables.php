<?php

use App\Enums\Classroom\EnrollmentStatus;
use App\Enums\Classroom\TeacherRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->enum('status', EnrollmentStatus::cases())
                ->default(EnrollmentStatus::ACTIVE);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('dropped_at')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
            $table->index(['course_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('course_teachers', function (Blueprint $table) {
            $table->id();
            $table->enum('role', TeacherRole::cases())
                ->default(TeacherRole::CO_TEACHER);
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_teachers');
        Schema::dropIfExists('course_enrollments');
    }
};
