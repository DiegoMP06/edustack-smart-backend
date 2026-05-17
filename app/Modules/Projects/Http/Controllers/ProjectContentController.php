<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Models\Projects\Project;
use App\Modules\Projects\Application\Support\ProjectDataMapper;
use App\Modules\Projects\Application\UseCases\Command\UpdateProjectContentAction;
use App\Modules\Projects\Http\Requests\UpdateProjectContentRequest;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectContentController extends Controller
{
    public function __construct(
        protected ProjectDataMapper $projectDataMapper,
    ) {}

    public function edit(Project $project, Request $request)
    {
        $this->authorize('update', $project);

        return inertia('projects/project-content', [
            'project' => $this->projectDataMapper->forContent($project),
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(
        Project $project,
        UpdateProjectContentRequest $request,
        UpdateProjectContentAction $action,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $edit = $request->boolean('edit', false);
        $data = ModelContentFormData::from($request->validated());
        $action->execute($project, $data);

        $route = $edit
            ? back()
            : redirect()->intended(route(
                'projects.collaborators.index',
                ['project' => $project, 'edit' => false],
                false,
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
