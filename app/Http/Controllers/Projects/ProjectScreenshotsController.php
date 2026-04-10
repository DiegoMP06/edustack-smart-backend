<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Projects\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectScreenshotsController extends Controller
{
    public function store(StoreModelMediaRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = $request->validated();

        foreach ($data['images'] as $key) {
            $project->addMediaFromDisk($key, 's3')
                ->toMediaCollection('screenshots');
        }

        return back()->with('message', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Request $request, Project $project, Media $media)
    {
        $this->authorize('update', $project);

        abort_if($media->model_type !== Project::class || $media->model_id !== $project->id, 404);

        if ($project->media()->count() == 1) {
            throw ValidationException::withMessages([
                'image' => 'El proyecto debe tener al menos una imagen.',
            ]);
        }

        $media->delete();

        return back()->with('message', 'Proyecto actualizado correctamente.');
    }
}
