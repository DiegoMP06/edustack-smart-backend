<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use App\Modules\Forms\DTOs\FormMediaData;

class StoreFormMediaAction
{
    public function execute(Form $form, FormMediaData $data): void
    {
        foreach ($data->images as $key) {
            $form->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }
    }
}
