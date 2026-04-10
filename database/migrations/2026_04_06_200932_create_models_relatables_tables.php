<?php

use App\Enums\Relations\RelatableContext;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('relatable_id');
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['post_id', 'context']);
            $table->unique(
                ['post_id', 'relatable_type', 'relatable_id', 'context'],
                'post_relatables_unique'
            );
        });

        Schema::create('project_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('relatable_id');
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['project_id', 'context']);
            $table->unique(
                ['project_id', 'relatable_type', 'relatable_id', 'context'],
                'project_relatables_unique'
            );
        });

        Schema::create('event_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('relatable_id');
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['event_id', 'context']);
            $table->unique(
                ['event_id', 'relatable_type', 'relatable_id', 'context'],
                'event_relatables_unique'
            );
        });

        Schema::create('event_activity_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->foreignId('event_activity_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('relatable_id');
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['event_activity_id', 'context']);
            $table->unique(
                ['event_activity_id', 'relatable_type', 'relatable_id', 'context'],
                'event_activity_relatables_unique'
            );
        });

        Schema::create('course_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('relatable_id');
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['course_id', 'context']);
            $table->unique(
                ['course_id', 'relatable_type', 'relatable_id', 'context'],
                'course_relatables_unique'
            );
        });

        Schema::create('course_lesson_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->unsignedBigInteger('relatable_id');
            $table->foreignId('course_lesson_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['course_lesson_id', 'context']);
            $table->unique(
                ['course_lesson_id', 'relatable_type', 'relatable_id', 'context'],
                'course_lesson_relatables_unique'
            );
        });

        Schema::create('form_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('relatable_id');
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['form_id', 'context']);
            $table->unique(
                ['form_id', 'relatable_type', 'relatable_id', 'context'],
                'form_relatables_unique'
            );
        });

        Schema::create('assignment_relatables', function (Blueprint $table) {
            $table->id();
            $table->string('relatable_type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('context', RelatableContext::cases())
                ->default(RelatableContext::RELATED);
            $table->unsignedBigInteger('relatable_id');
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->index(['relatable_type', 'relatable_id']);
            $table->index(['assignment_id', 'context']);
            $table->unique(
                ['assignment_id', 'relatable_type', 'relatable_id', 'context'],
                'assignment_relatables_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_relatables');
        Schema::dropIfExists('project_relatables');
        Schema::dropIfExists('event_relatables');
        Schema::dropIfExists('event_activity_relatables');
        Schema::dropIfExists('course_relatables');
        Schema::dropIfExists('course_lesson_relatables');
        Schema::dropIfExists('form_relatables');
        Schema::dropIfExists('assignment_relatables');
    }
};
