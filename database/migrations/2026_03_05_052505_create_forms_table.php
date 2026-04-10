<?php

use App\Enums\Forms\ContextFormAttachment;
use App\Enums\Forms\ShowResultsToRespondent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('collect_email')->default(false);
            $table->string('redirect_url')->nullable();
            $table->text('confirmation_message')->nullable();
            $table->unsignedInteger('max_responses')->nullable();
            $table->unsignedTinyInteger('max_attempts')->default(1);
            $table->boolean('show_progress_bar')->default(true);
            $table->boolean('shuffle_sections')->default(false);
            $table->boolean('allow_multiple_responses')->default(false);
            $table->boolean('requires_login')->default(true);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('randomize_options')->default(false);
            $table->boolean('is_quiz_mode')->default(false);
            $table->decimal('passing_score', 5, 2)->nullable();
            $table->boolean('show_correct_answers')->default(false);
            $table->enum('show_results_to_respondent', ShowResultsToRespondent::cases())
                ->default(ShowResultsToRespondent::IMMEDIATELY);
            $table->boolean('show_feedback_after')->default(true);
            $table->unsignedSmallInteger('time_limit_minutes')->nullable();
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_until')->nullable();
            $table->foreignId('form_type_id')
                ->constrained('form_types')->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['form_type_id', 'is_published', 'is_active']);
        });

        Schema::create('form_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->enum('context', ContextFormAttachment::cases())
                ->default(ContextFormAttachment::CUSTOM);
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->json('settings_override')->nullable();
            $table->nullableMorphs('formable');
            $table->foreignId('form_id')
                ->constrained('forms')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(
                ['form_id', 'formable_type', 'formable_id', 'context'],
                'form_attachments_unique'
            );
            $table->index(['formable_type', 'formable_id', 'context']);
            $table->index(['formable_type', 'formable_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_attachments');
        Schema::dropIfExists('forms');
    }
};
