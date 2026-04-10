<?php

namespace App\Http\Resources\Projects;

use App\Enums\Projects\ProjectCollaboratorRole;
use App\Http\Resources\Collaborators\UserCollaboratorResource;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['media'] = $this->getMedia('screenshots')->map(
            fn($m) => new MediaResource($m, 'main', [
                'main' => ['width' => 1200, 'height' => 620],
                'screenshot' => ['width' => 1920, 'height' => 1080]
            ])
        );

        return $data;
    }
}
