<?php

namespace App\Modules\Projects\Application\Support;

use App\Models\Projects\Project;
use App\Modules\Projects\Application\DTOs\ProjectData;

class ProjectDataMapper
{
    public function forIndex(Project $project): ProjectData
    {
        return ProjectData::fromModel(
            $project->load(['status', 'categories', 'media'])
        )->include('status', 'categories', 'media');
    }

    public function forShow(Project $project): ProjectData
    {
        return ProjectData::fromModel(
            $project->load(['status', 'categories', 'media', 'author', 'collaborators'])
        )->include('status', 'categories', 'media', 'author', 'collaborators');
    }

    public function forEdit(Project $project): ProjectData
    {
        return ProjectData::fromModel(
            $project->load(['status', 'categories', 'media', 'collaborators'])
        )->include('status', 'categories', 'media', 'collaborators');
    }

    public function forContent(Project $project): ProjectData
    {
        return ProjectData::fromModel($project);
    }

    public function forApiIndex(Project $project): ProjectData
    {
        return ProjectData::fromModel(
            $project->load(['categories', 'status', 'media', 'author'])
        )->include('categories', 'status', 'media', 'author');
    }

    public function forApiShow(Project $project): ProjectData
    {
        return ProjectData::fromModel(
            $project->load(['categories', 'status', 'media', 'author', 'collaborators'])
        )->include('categories', 'status', 'media', 'author', 'collaborators');
    }
}
