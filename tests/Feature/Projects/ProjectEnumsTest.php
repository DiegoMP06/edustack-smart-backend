<?php

use App\Enums\Projects\ProjectLicense;
use App\Enums\Projects\RolesOfProjectCollaborators;
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

it('casts project license to enum', function () {
    $project = Project::factory()->create([
        'license' => ProjectLicense::MIT,
    ]);

    expect($project->license)->toBeInstanceOf(ProjectLicense::class)
        ->and($project->license)->toBe(ProjectLicense::MIT);
});

it('casts project collaborator role to enum', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $collaborator = ProjectCollaborator::create([
        'project_id' => $project->id,
        'user_id' => $user->id,
        'role' => RolesOfProjectCollaborators::LEADER,
    ]);

    expect($collaborator->role)->toBeInstanceOf(RolesOfProjectCollaborators::class)
        ->and($collaborator->role)->toBe(RolesOfProjectCollaborators::LEADER);
});
