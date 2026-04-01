<?php

namespace App\Http\Resources\Classroom;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['media'] = $this->getMedia('cover')->map(
            fn ($media) => new MediaResource($media, 'original', ['original' => ['width' => 0, 'height' => 0]])
        );

        return $data;
    }
}
