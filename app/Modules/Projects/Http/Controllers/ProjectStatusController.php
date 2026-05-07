<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Projects\Project;
use App\Modules\Projects\Services\ProjectStatusService;

class ProjectStatusController extends Controller
{
    public function __construct(
        private ProjectStatusService $statusService,
    ) {}

    /**
     * Toggle the model status flag.
     */
    public function __invoke(Project $project)
    {
        $this->authorize('update', $project);

        $this->statusService->toggle($project);

        return back()->with('message', 'Project status updated successfully.');
    }
}
