<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormData;
use Illuminate\Support\Facades\DB;

class CreateFormAction
{
    /**
     * Persist a new model using DTO data.
     */
    public function execute(FormData $data, int $userId): Form
    {
        return DB::transaction(function () use ($userId) {
            $form = Form::create([
                // Map DTO properties to model attributes.
                'user_id' => $userId,
            ]);

            // Example: $form->addMedia($data->file)->toMediaCollection('default');

            return $form;
        });
    }
}
