<?php

namespace App\Modules\Events\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Modules\Events\Services\EventStatusService;

class EventStatusController extends Controller
{
    public function __construct(
        private EventStatusService $statusService,
    ) {}

    /**
     * Toggle the model status flag.
     */
    public function __invoke(Event $event)
    {
        $this->authorize('update', $event);

        $this->statusService->toggle($event);

        return back()->with('message', 'Event status updated successfully.');
    }
}
