<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormMediaDeletionData;
use Illuminate\Validation\ValidationException;

class DeleteFormMediaAction
{
    public function execute(Form $form, FormMediaDeletionData $data): void
    {
        $media = $data->media;

        abort_if($media->model_type !== Form::class || $media->model_id !== $form->id, 404);

        if ($form->media()->count() === 1) {
            throw ValidationException::withMessages([
                'image' => 'At least one image is required.',
            ]);
        }

        $media->delete();
    }
}
