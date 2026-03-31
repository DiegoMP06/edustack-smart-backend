<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\UpdateProjectContentRequest;
use App\Models\Projects\Project;
use Illuminate\Http\Request;

class ProjectContentController extends Controller
{
    public function edit(Project $project, Request $request)
    {
        $edit = $request->boolean('edit', false);

        return inertia('projects/project-content', [
            'project' => $project,
            'edit' => $edit,
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(Project $project, UpdateProjectContentRequest $request)
    {
        $edit = $request->boolean('edit', false);
        $data = $request->validated();

        $project->content = $data['content'];
        $project->save();

        $route = $edit ?
            back() :
            redirect()->intended(route(
                'project-collaborators.index',
                ['project' => $project, 'edit' => false],
                false
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
