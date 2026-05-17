<?php

namespace App\Modules\Projects\Application\UseCases\Command;

use App\Models\Projects\Project;
use Illuminate\Support\Facades\DB;

class DeleteProjectAction
{
    /**
     * Delete the model in a transaction.
     */
    public function execute(Project $project): void
    {
        DB::transaction(function () use ($project) {
            $project->deleteOrFail();
        });
    }
}
