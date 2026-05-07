<?php

namespace App\Modules\Forms\Services;

use App\Models\Forms\Form;
use App\Modules\Forms\Actions\DeleteFormMediaAction;
use App\Modules\Forms\Actions\StoreFormMediaAction;
use App\Modules\Forms\DTOs\FormMediaData;
use App\Modules\Forms\DTOs\FormMediaDeletionData;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FormMediaService
{
    public function __construct(
        private StoreFormMediaAction $storeMediaAction,
        private DeleteFormMediaAction $deleteMediaAction,
    ) {}

    public function store(Form $form, FormMediaData $data): void
    {
        $this->storeMediaAction->execute($form, $data);
    }

    public function destroy(Form $form, Media $media): void
    {
        $this->deleteMediaAction->execute(
            $form,
            new FormMediaDeletionData($media),
        );
    }
}
