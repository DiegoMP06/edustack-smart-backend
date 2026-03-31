<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\StoreProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Resources\Projects\ProjectCollection;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectCategory;
use App\Models\Projects\ProjectStatus;
use App\Traits\ApiQueryable;
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
        $projects = $this->buildQuery(
            $request->user()->projects(),
            defaultIncludes: ['status', 'categories', 'media']
        )->paginate(20)->withQueryString();

        return Inertia::render('projects/projects', [
            ...$this->formData(),
            'projects' => new ProjectCollection($projects),
            'filter' => $request->query('filter'),
            'message' => session()->get('message'),
        ]);
    }

    public function create()
    {
        return Inertia::render('projects/create-project', $this->formData());
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        $project = $request->user()->projects()->create([
            'name' => $data['name'],
            'summary' => $data['summary'],
            'repository_url' => $data['repository_url'],
            'demo_url' => $data['demo_url'],
            'tech_stack' => $data['tech_stack'],
            'version' => $data['version'],
            'license' => $data['license'],
            'project_status_id' => $data['project_status_id'],
            'content' => [],
        ]);

        $project->categories()->sync($data['categories']);

        foreach ($request->file('images') as $file) {
            $project->addMedia($file)->toMediaCollection('screenshots');
        }

        return redirect()->intended(
            route('projects.content.edit', ['project' => $project, 'edit' => false], false)
        )->with('message', 'Proyecto creado correctamente.');
    }

    public function show(Project $project)
    {
        return Inertia::render('projects/show-project', [
            'project' => (new ProjectCollection([$project->load(['collaborators', 'author', 'media', 'categories', 'status'])]))->first(),
        ]);
    }

    public function edit(Project $project)
    {
        return Inertia::render('projects/edit-project', [
            ...$this->formData(),
            'project' => (new ProjectCollection([$project->load(['media', 'categories', 'status'])]))->first(),
            'message' => session()->get('message'),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        $project->update($data);
        $project->categories()->sync($data['categories']);

        return back()->with('message', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return back()->with('message', 'Proyecto eliminado correctamente.');
    }
}
