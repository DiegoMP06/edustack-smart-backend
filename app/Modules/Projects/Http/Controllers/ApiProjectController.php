<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Models\Projects\Project;
use App\Modules\Projects\Application\UseCases\Query\ListPublishedProjectsAction;
use App\Modules\Projects\Application\UseCases\Query\ShowPublishedProjectAction;
use App\Modules\Projects\Http\Resources\ProjectCollection;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiProjectController extends Controller
{
    public function __construct(
        protected ListPublishedProjectsAction $listPublishedProjectsAction,
        protected ShowPublishedProjectAction $showPublishedProjectAction,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $data = ListCollectionQueryParamsData::fromRequest($request);
        $projects = $this->listPublishedProjectsAction->execute($data);

        return response()->json(new ProjectCollection($projects));
    }

    public function show(Project $project): JsonResponse
    {
        $project = $this->showPublishedProjectAction->execute($project);

        return response()->json($project);
    }
}
