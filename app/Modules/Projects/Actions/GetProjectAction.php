<?php

namespace App\Modules\Projects\Actions;

use App\Models\Projects\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetProjectAction
{
    /**
     * Retrieve a model by its primary key.
     *
     * @throws ModelNotFoundException
     */
    public function execute(int $id): Project
    {
        $project = Project::find($id);

        if (! $project) {
            throw new ModelNotFoundException("The record with ID {$id} was not found.");
        }

        // If you prefer returning a DTO, map it here and adjust the return type.

        return $project;
    }
}
