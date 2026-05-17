<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Models\Projects\Project;
use App\Modules\Projects\Application\UseCases\Command\ToggleProjectStatusAction;
use Illuminate\Http\RedirectResponse;

class ProjectStatusController extends Controller
{
    public function __invoke(Project $project, ToggleProjectStatusAction $action): RedirectResponse
    {
        $this->authorize('update', $project);

        $action->execute($project);

        return back()->with('message', 'El estado del proyecto ha sido actualizado.');
    }
}
