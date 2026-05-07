<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectContentData;
use App\Modules\Projects\Http\Requests\UpdateProjectContentRequest;
use App\Modules\Projects\Services\ProjectContentService;
use Illuminate\Http\Request;

class ProjectContentController extends Controller
{
    public function __construct(
        private ProjectContentService $contentService,
    ) {}

    /**
     * Show the content editor for the given model.
     */
    public function edit(Project $project, Request $request)
    {
        $this->authorize('update', $project);

        return inertia('projects/project-content', [
            'project' => $project,
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Persist editor content for the given model.
     */
    public function update(Project $project, UpdateProjectContentRequest $request)
    {
        $this->authorize('update', $project);

        $data = ProjectContentData::fromArray($request->validated());
        $this->contentService->update($project, $data);

        return back()->with('message', 'Project content saved successfully.');
    }
}
