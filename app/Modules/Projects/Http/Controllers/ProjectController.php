<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Models\Projects\Project;
use App\Modules\Projects\Application\DTOs\DraftProjectFormData;
use App\Modules\Projects\Application\Support\ProjectDataMapper;
use App\Modules\Projects\Application\UseCases\Command\CreateProjectAction;
use App\Modules\Projects\Application\UseCases\Command\DeleteProjectAction;
use App\Modules\Projects\Application\UseCases\Command\UpdateProjectAction;
use App\Modules\Projects\Application\UseCases\Query\GetProjectFormOptionsAction;
use App\Modules\Projects\Application\UseCases\Query\ListUserProjectsAction;
use App\Modules\Projects\Http\Requests\StoreProjectRequest;
use App\Modules\Projects\Http\Requests\UpdateProjectRequest;
use App\Modules\Projects\Http\Resources\ProjectCollection;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        protected ListUserProjectsAction $listUserProjectsAction,
        protected GetProjectFormOptionsAction $getProjectFormOptionsAction,
        protected ProjectDataMapper $projectDataMapper,
    ) {}

    private function forCreateForm(): array
    {
        return $this->getProjectFormOptionsAction->execute();
    }

    private function forEditForm(): array
    {
        return $this->forCreateForm();
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Project::class);

        $data = ListCollectionQueryParamsData::fromRequest($request);
        $projects = $this->listUserProjectsAction->execute($data, $request->user());

        return inertia('projects/projects', [
            'projects' => new ProjectCollection($projects),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Project::class);

        return inertia('projects/create-project', $this->forCreateForm());
    }

    public function store(
        StoreProjectRequest $request,
        CreateProjectAction $action
    ): RedirectResponse {
        $this->authorize('create', Project::class);

        $data = DraftProjectFormData::from($request->validated());
        $project = $action->execute($data, $request->user());

        return redirect()->intended(
            route('projects.content.edit', ['project' => $project, 'edit' => false], false)
        )->with('message', 'Proyecto creado correctamente.');
    }

    public function show(Project $project): Response
    {
        $this->authorize('view', $project);

        return inertia('projects/show-project', [
            'project' => $this->projectDataMapper->forShow($project),
        ]);
    }

    public function edit(Project $project, Request $request): Response
    {
        $this->authorize('update', $project);

        return inertia('projects/edit-project', [
            ...$this->forEditForm(),
            'project' => $this->projectDataMapper->forEdit($project),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project, UpdateProjectAction $action): RedirectResponse
    {
        $this->authorize('update', $project);

        $data = DraftProjectFormData::from($request->validated());
        $action->execute($project, $data);

        return back()->with('message', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project, DeleteProjectAction $action): RedirectResponse
    {
        $this->authorize('delete', $project);

        $action->execute($project);

        return back()->with('message', 'Proyecto eliminado correctamente.');
    }
}
