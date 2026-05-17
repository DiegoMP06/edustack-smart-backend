<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Models\Projects\Project;
use App\Models\Projects\ProjectCollaborator;
use App\Modules\Admin\Http\Resources\UserCollection;
use App\Modules\Projects\Application\DTOs\ProjectCollaboratorFormData;
use App\Modules\Projects\Application\Support\ProjectDataMapper;
use App\Modules\Projects\Application\UseCases\Command\DeleteCollaboratorAction;
use App\Modules\Projects\Application\UseCases\Command\StoreCollaboratorAction;
use App\Modules\Projects\Http\Requests\StoreProjectCollaboratorRequest;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use App\Modules\Shared\Services\UsersService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectCollaboratorsController extends Controller
{
    public function __construct(
        protected UsersService $usersService,
        protected ProjectDataMapper $projectDataMapper,
    ) {}

    public function index(Project $project, Request $request): Response
    {
        $this->authorize('update', $project);

        $data = ListCollectionQueryParamsData::fromRequest($request);
        $users = $this->usersService->listActiveUsers($data, $request->user());

        return inertia('projects/project-collaborators', [
            'project' => $this->projectDataMapper->forEdit($project),
            'users' => new UserCollection($users),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
            'edit' => $request->boolean('edit', false),
        ]);
    }

    public function store(
        Project $project,
        StoreProjectCollaboratorRequest $request,
        StoreCollaboratorAction $action,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $data = ProjectCollaboratorFormData::from($request->validated());
        $action->execute($data, $project);

        return back()->with('message', 'Colaborador agregado correctamente.');
    }

    public function destroy(
        Project $project,
        ProjectCollaborator $projectCollaborator,
        DeleteCollaboratorAction $action,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $action->execute($project, $projectCollaborator);

        return back()->with('message', 'Colaborador eliminado correctamente.');
    }
}
