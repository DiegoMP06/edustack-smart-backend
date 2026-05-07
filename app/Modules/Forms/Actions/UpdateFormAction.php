<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormData;
use Illuminate\Support\Facades\DB;

class UpdateFormAction
{
    /**
     * Update an existing model using DTO data.
     */
    public function execute(Form $form, FormData $data): Form
    {
        return DB::transaction(function () use ($form) {
            $form->update([
                // Map DTO properties to model attributes.
            ]);

            return $form->load([]);
        });
    }
}
