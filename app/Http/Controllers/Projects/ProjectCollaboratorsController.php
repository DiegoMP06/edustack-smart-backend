<?php

namespace App\Http\Controllers\Projects;

use App\Enums\Projects\ProjectCollaboratorRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\StoreProjectCollaboratorRequest;
use App\Http\Resources\UserCollection;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectCollaborator;
use App\Models\User;
use App\Traits\ApiQueryable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProjectCollaboratorsController extends Controller
{
    use ApiQueryable;

    public function index(Project $project, Request $request)
    {
        $this->authorize('update', $project);

        $users = $users = $this->buildQuery(
            User::where(
                fn($query) =>
                $query->whereNot('id', $request->user()?->id)
                    ->where('is_active', true)
            ),
        )->paginate(20)->withQueryString();

        return Inertia::render('projects/project-collaborators', [
            'users' => new UserCollection($users),
            'filter' => $request->query('filter'),
            'project' => $project->load('collaborators'),
            'roles' => ProjectCollaboratorRole::cases(),
            'message' => $request->session()->get('message'),
            'edit' => $request->boolean('edit', false),
        ]);
    }

    public function store(Project $project, StoreProjectCollaboratorRequest $request)
    {
        $this->authorize('update', $project);

        $data = $request->validated();

        if ($project->collaborators()->wherePivot('user_id', $data['user_id'])->exists()) {
            throw ValidationException::withMessages(['user_id' => 'El colaborador ya pertenece al proyecto.']);
        }

        $project->collaborators()->attach($data['user_id'], ['role' => $data['role']]);

        return back()->with('message', 'Colaborador agregado correctamente.');
    }

    public function destroy(Project $project, ProjectCollaborator $projectCollaborator)
    {
        $this->authorize('update', $project);

        abort_if($projectCollaborator->project_id !== $project->id, 404);

        $projectCollaborator->delete();

        return back()->with('message', 'Colaborador eliminado correctamente.');
    }
}
