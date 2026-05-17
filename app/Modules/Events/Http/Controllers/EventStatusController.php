<?php

namespace App\Modules\Events\Http\Controllers;

use App\Models\Events\Event;
use App\Modules\Events\Actions\ToggleEventStatusAction;
use Illuminate\Http\RedirectResponse;

class EventStatusController extends Controller
{
    /**
     * Toggle the model status flag.
     */
    public function __invoke(
        Event $event,
        ToggleEventStatusAction $action
    ): RedirectResponse {
        $this->authorize('update', $event);

        $action->execute($event);

        return back()->with('message', 'Event status updated successfully.');
    }
}
