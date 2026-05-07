<?php

namespace App\Modules\Forms\Actions;

use App\Models\Forms\Form;
use Illuminate\Support\Facades\DB;

class DeleteFormAction
{
    /**
     * Delete the model in a transaction.
     */
    public function execute(Form $form): void
    {
        DB::transaction(function () use ($form) {
            // Example: $form->clearMediaCollection();
            $form->deleteOrFail();
        });
    }
}
