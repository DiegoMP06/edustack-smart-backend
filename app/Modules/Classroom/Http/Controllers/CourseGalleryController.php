<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreModelMediaRequest;
use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseMediaData;
use App\Modules\Classroom\Services\CourseMediaService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CourseGalleryController extends Controller
{
    public function __construct(
        private CourseMediaService $mediaService,
    ) {}

    /**
     * Add uploaded media keys to the model collection.
     */
    public function store(StoreModelMediaRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = CourseMediaData::fromArray($request->validated());
        $this->mediaService->store($course, $data);

        return back()->with('message', 'Course media updated successfully.');
    }

    /**
     * Remove media from the model collection.
     */
    public function destroy(Course $course, Media $media)
    {
        $this->authorize('update', $course);

        $this->mediaService->destroy($course, $media);

        return back()->with('message', 'Course media updated successfully.');
    }
}
