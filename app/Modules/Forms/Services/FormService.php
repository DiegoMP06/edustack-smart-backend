<?php

namespace App\Modules\Forms\Services;

use App\Models\Forms\Form;
use App\Modules\Forms\Actions\CreateFormAction;
use App\Modules\Forms\Actions\DeleteFormAction;
use App\Modules\Forms\Actions\UpdateFormAction;
use App\Modules\Forms\DTOs\FormData;
use Illuminate\Pagination\LengthAwarePaginator;

class FormService
{
    public function __construct(
        private CreateFormAction $createAction,
        private UpdateFormAction $updateAction,
        private DeleteFormAction $deleteAction,
    ) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        return Form::query()
            ->with([])
            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where('title', 'like', "%{$value}%"))
            ->latest()
            ->paginate(15);
    }

    public function findOrFail(int $id): Form
    {
        return Form::with([])->findOrFail($id);
    }

    public function create(FormData $data, int $userId): Form
    {
        return $this->createAction->execute($data, $userId);
    }

    public function update(Form $form, FormData $data): Form
    {
        return $this->updateAction->execute($form, $data);
    }

    public function delete(Form $form): void
    {
        $this->deleteAction->execute($form);
    }
}
