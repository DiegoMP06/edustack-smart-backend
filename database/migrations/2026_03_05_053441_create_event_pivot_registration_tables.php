<?php

use App\Enums\Events\ActivityRegistrationStatus;
use App\Enums\Events\EventCollaboratorRole;
use App\Enums\Events\EventRegistrationStatus;
use App\Enums\Events\TeamMemberRole;
use App\Enums\Events\TeamStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_activity_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_activity_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['event_activity_id', 'event_activity_category_id']);
        });

        Schema::create('event_collaborators', function (Blueprint $table) {
            $table->id();
            $table->enum('role', EventCollaboratorRole::cases())
                ->default(EventCollaboratorRole::COLLABORATOR);
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->enum('status', EventRegistrationStatus::cases())
            ->default(EventRegistrationStatus::PENDING);
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
            $table->index(['event_id', 'status']);
        });

        Schema::create('event_activity_registrations', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ActivityRegistrationStatus::cases())
                ->default(ActivityRegistrationStatus::REGISTERED);
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_activity_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'event_activity_id']);
            $table->index(['event_activity_id', 'status']);
        });

        Schema::create('event_activity_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', TeamStatus::cases())
                ->default(TeamStatus::FORMING);
            $table->foreignId('captain_user_id')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('event_activity_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['event_activity_id', 'status']);
        });


        Schema::create('event_activity_team_members', function (Blueprint $table) {
            $table->id();
            $table->enum('role', TeamMemberRole::cases())
                ->default(TeamMemberRole::MEMBER);
            $table->timestamp('joined_at')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_activity_team_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'event_activity_team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_activity_team_members');
        Schema::dropIfExists('event_activity_teams');
        Schema::dropIfExists('event_activity_registrations');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('event_collaborators');
        Schema::dropIfExists('event_activity_category');
    }
};
