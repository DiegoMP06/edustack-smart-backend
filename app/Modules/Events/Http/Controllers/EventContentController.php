<?php

namespace App\Modules\Events\Http\Controllers;

use App\Models\Events\Event;
use App\Modules\Events\Actions\UpdateEventContentAction;
use App\Modules\Events\DTOs\EventData;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;
use App\Modules\Shared\Http\Requests\UpdateModelContentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class EventContentController extends Controller
{
    /**
     * Show the content editor for the given model.
     */
    public function edit(Event $event, Request $request): Response
    {
        $this->authorize('update', $event);

        return inertia('events/event-content', [
            'event' => EventData::from($event),
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Persist editor content for the given model.
     */
    public function update(
        Event $event,
        UpdateModelContentRequest $request,
        UpdateEventContentAction $action
    ): RedirectResponse {
        $this->authorize('update', $event);
        $edit = $request->boolean('edit', false);
        $data = ModelContentFormData::from($request->validated());
        $action->execute($event, $data);

        $route = $edit
            ? back()
            : redirect()->intended(route('projects.index', absolute: false));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
