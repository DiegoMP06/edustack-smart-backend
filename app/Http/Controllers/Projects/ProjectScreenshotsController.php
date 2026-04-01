<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\StoreProjectScreenshotsRequest;
use App\Models\Projects\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectScreenshotsController extends Controller
{
    public function store(StoreProjectScreenshotsRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = $request->validated();

        $images = $request->file('images');

        foreach ($images as $file) {
            $project->addMedia($file)
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
