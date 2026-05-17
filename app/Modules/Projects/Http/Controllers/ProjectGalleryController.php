<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Models\Projects\Project;
use App\Modules\Media\DTOs\ModelMediaFormData;
use App\Modules\Media\Http\Requests\StoreModelMediaRequest;
use App\Modules\Projects\Application\UseCases\Command\DeleteProjectMediaAction;
use App\Modules\Projects\Application\UseCases\Command\StoreProjectMediaAction;
use Illuminate\Http\RedirectResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectGalleryController extends Controller
{
    public function store(
        StoreModelMediaRequest $request,
        Project $project,
        StoreProjectMediaAction $action,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $data = ModelMediaFormData::from($request->validated());
        $action->execute($project, $data);

        return back()->with('message', 'Proyecto actualizado correctamente.');
    }

    public function destroy(
        Project $project,
        Media $media,
        DeleteProjectMediaAction $action,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $action->execute($project, $media);

        return back()->with('message', 'Proyecto actualizado correctamente.');
    }
}
