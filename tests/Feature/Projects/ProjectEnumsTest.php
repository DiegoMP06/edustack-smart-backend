<?php

use App\Enums\Projects\ProjectCollaboratorRole;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectCollaborator;
use App\Models\Projects\ProjectStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('project status slug is a string (enum cast removed for compatibility with sluggable)', function () {
    $status = ProjectStatus::factory()->create([
        'slug' => 'in-progress',
    ]);

    expect($status->slug)->toBe('in-progress');
});

it('keeps project license as plain string', function () {
    $project = Project::factory()->create([
        'license' => 'MIT',
    ]);

    expect($project->license)->toBe('MIT');
});

it('casts project collaborator role to enum', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $collaborator = ProjectCollaborator::create([
        'project_id' => $project->id,
        'user_id' => $user->id,
        'role' => ProjectCollaboratorRole::LEADER,
    ]);

    expect($collaborator->role)->toBeInstanceOf(ProjectCollaboratorRole::class)
        ->and($collaborator->role)->toBe(ProjectCollaboratorRole::LEADER);
});
