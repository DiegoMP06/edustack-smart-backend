<?php

namespace App\Http\Controllers\API;

use App\Concerns\ApiQueryable;
use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectCollection;
use App\Models\Projects\Project;
use Illuminate\Http\Request;

class ApiProjectsController extends Controller
{
    use ApiQueryable;

    public function index(Request $request)
    {
        $projects = $this->buildQuery(
            Project::where('is_published', true),
            defaultIncludes: [
                'status',
                'categories',
                'author',
                'collaborators',
                'media'
            ]
        )->paginate(20)->withQueryString();

        return new ProjectCollection($projects);
    }

    public function show(Request $request, Project $project)
    {
        if (!$project->is_published) {
            return response()->json(['message' => 'Project not found.'], 404);
        }

        return response()->json(
            (new ProjectCollection([
                $project->load([
                    'status',
                    'categories',
                    'author',
                    'collaborators',
                    'media'
                ]),
            ]))->first()
        );
    }
}
