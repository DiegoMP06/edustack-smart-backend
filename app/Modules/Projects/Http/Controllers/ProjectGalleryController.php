<?php

namespace App\Modules\Projects\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Projects\Project;
use App\Modules\Projects\DTOs\ProjectMediaData;
use App\Modules\Projects\Services\ProjectMediaService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectGalleryController extends Controller
{
    public function __construct(
        private ProjectMediaService $mediaService,
    ) {}

    /**
     * Add uploaded media keys to the model collection.
     */
    public function store(StoreModelMediaRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = ProjectMediaData::fromArray($request->validated());
        $this->mediaService->store($project, $data);

        return back()->with('message', 'Project media updated successfully.');
    }

    /**
     * Remove media from the model collection.
     */
    public function destroy(Project $project, Media $media)
    {
        $this->authorize('update', $project);

        $this->mediaService->destroy($project, $media);

        return back()->with('message', 'Project media updated successfully.');
    }
}
