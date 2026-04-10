<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\StoreProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Resources\Projects\ProjectCollection;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectCategory;
use App\Models\Projects\ProjectStatus;
use App\Concerns\ApiQueryable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    use ApiQueryable;

    private function formData(): array
    {
        return [
            'statuses' => ProjectStatus::orderBy('order')->get(),
            'categories' => ProjectCategory::orderBy('order')->get(),
        ];
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $projects = $this->buildQuery(
            $request->user()->projects(),
            defaultIncludes: ['status', 'categories', 'media']
        )->paginate(20)->withQueryString();

        return Inertia::render('projects/projects', [
            'projects' => new ProjectCollection($projects),
            'filter' => $request->query('filter'),
            'message' => session()->get('message'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        return Inertia::render('projects/create-project', $this->formData());
    }

    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();

        $project = $request->user()->projects()->create([
            ...$data,
            'content' => [],
        ]);

        $project->categories()->sync($data['categories']);

        foreach ($data['images'] as $key) {
            $project->addMediaFromDisk($key, 's3')
                ->toMediaCollection('screenshots');
        }

        return redirect()->intended(
            route('projects.content.edit', ['project' => $project, 'edit' => false], false)
        )->with('message', 'Proyecto creado correctamente.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return Inertia::render('projects/show-project', [
            'project' => (new ProjectCollection([$project->load(['collaborators', 'author', 'media', 'categories', 'status'])]))->first(),
        ]);
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return Inertia::render('projects/edit-project', [
            ...$this->formData(),
            'project' => (new ProjectCollection([$project->load(['media', 'categories', 'status'])]))->first(),
            'message' => session()->get('message'),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = $request->validated();

        $project->update($data);
        $project->categories()->sync($data['categories']);

        return back()->with('message', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return back()->with('message', 'Proyecto eliminado correctamente.');
    }
}
