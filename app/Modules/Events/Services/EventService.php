<?php

namespace App\Modules\Events\Services;

use App\Models\Events\Event;
use App\Modules\Events\Actions\CreateEventAction;
use App\Modules\Events\Actions\DeleteEventAction;
use App\Modules\Events\Actions\UpdateEventAction;
use App\Modules\Events\DTOs\EventData;
use Illuminate\Pagination\LengthAwarePaginator;

class EventService
{
    public function __construct(
        private CreateEventAction $createAction,
        private UpdateEventAction $updateAction,
        private DeleteEventAction $deleteAction,
    ) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        return Event::query()
            ->with([])
            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where('title', 'like', "%{$value}%"))
            ->latest()
            ->paginate(15);
    }

    public function findOrFail(int $id): Event
    {
        return Event::with([])->findOrFail($id);
    }

    public function create(EventData $data, int $userId): Event
    {
        return $this->createAction->execute($data, $userId);
    }

    public function update(Event $event, EventData $data): Event
    {
        return $this->updateAction->execute($event, $data);
    }

    public function delete(Event $event): void
    {
        $this->deleteAction->execute($event);
    }
}
