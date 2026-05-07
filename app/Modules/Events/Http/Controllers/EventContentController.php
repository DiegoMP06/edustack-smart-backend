<?php

namespace App\Modules\Events\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events\Event;
use App\Modules\Events\DTOs\EventContentData;
use App\Modules\Events\Http\Requests\UpdateEventContentRequest;
use App\Modules\Events\Services\EventContentService;
use Illuminate\Http\Request;

class EventContentController extends Controller
{
    public function __construct(
        private EventContentService $contentService,
    ) {}

    /**
     * Show the content editor for the given model.
     */
    public function edit(Event $event, Request $request)
    {
        $this->authorize('update', $event);

        return inertia('events/event-content', [
            'event' => $event,
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Persist editor content for the given model.
     */
    public function update(Event $event, UpdateEventContentRequest $request)
    {
        $this->authorize('update', $event);

        $data = EventContentData::fromArray($request->validated());
        $this->contentService->update($event, $data);

        return back()->with('message', 'Event content saved successfully.');
    }
}
