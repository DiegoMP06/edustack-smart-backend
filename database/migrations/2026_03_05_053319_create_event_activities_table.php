<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('content');
            $table->text('requirements')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('online_link')->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->boolean('has_teams')->default(false);
            $table->boolean('requires_team')->default(false);
            $table->unsignedSmallInteger('min_team_size')->nullable();
            $table->unsignedSmallInteger('max_team_size')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('only_students')->default(true);
            $table->boolean('is_competition')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->json('speakers')->nullable();
            $table->string('repository_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->dateTime('started_at')->index();
            $table->dateTime('ended_at');
            $table->dateTime('registration_started_at');
            $table->dateTime('registration_ended_at');
            $table->foreignId('difficulty_level_id')->nullable()
                ->constrained()->nullOnDelete();
            $table->foreignId('event_status_id')->constrained()->restrictOnDelete();
            $table->foreignId('event_activity_type_id')->nullable()
                ->constrained()->nullOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['event_id', 'is_published']);
            $table->index(['started_at', 'ended_at']);
            $table->index(['event_id', 'event_activity_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_activities');
    }
};
