<?php

use App\Models\Projects\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['scout.driver' => null]);

    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'student']);
});

it('allows collaborators to view project but forbids updating it', function (): void {
    $owner = User::factory()->member()->create();
    $collaborator = User::factory()->student()->create();

    $project = Project::factory()->for($owner)->create();
    $project->collaborators()->attach($collaborator->id, ['role' => 'collaborator']);

    $this->actingAs($collaborator)
        ->get(route('projects.show', $project))
        ->assertSuccessful();

    $this->actingAs($collaborator)
        ->patch(route('projects.status', $project))
        ->assertForbidden();
});

it('forbids unrelated users from viewing projects', function (): void {
    $owner = User::factory()->member()->create();
    $otherUser = User::factory()->student()->create();

    $project = Project::factory()->for($owner)->create();

    $this->actingAs($otherUser)
        ->get(route('projects.show', $project))
        ->assertForbidden();
});
