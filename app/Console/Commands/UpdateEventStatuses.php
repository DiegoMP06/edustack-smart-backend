<?php

namespace App\Console\Commands;

use App\Enums\Events\EventStatusSlug;
use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\Events\EventStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-event-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de los eventos según la fecha actual';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $statuses = EventStatus::whereIn('slug', array_column(EventStatusSlug::cases(), 'value'))
            ->get()
            ->pluck('id', 'slug');

        Event::where('event_status_id', $statuses[EventStatusSlug::ONGOING->value])
            ->where('registration_started_at', '<=', $now)
            ->where('registration_ended_at', '>=', $now)
            ->update(['event_status_id' => $statuses[EventStatusSlug::OPEN->value]]);

        Event::where('event_status_id', $statuses[EventStatusSlug::OPEN->value])
            ->where('registration_ended_at', '<=', $now)
            ->update(['event_status_id' => $statuses[EventStatusSlug::CLOSED->value]]);

        Event::where('event_status_id', $statuses[EventStatusSlug::CLOSED->value])
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->update(['event_status_id' => $statuses[EventStatusSlug::ONGOING->value]]);

        Event::where('event_status_id', '!=', $statuses[EventStatusSlug::FINISHED->value])
            ->whereDate('end_date', '<', $now)
            ->update(['event_status_id' => $statuses[EventStatusSlug::FINISHED->value]]);

        EventActivity::where('event_status_id', $statuses[EventStatusSlug::ONGOING->value])
            ->where('started_at', '<=', $now)
            ->where('ended_at', '>=', $now)
            ->update(['event_status_id' => $statuses[EventStatusSlug::ONGOING->value]]);

        EventActivity::where('event_status_id', $statuses[EventStatusSlug::ONGOING->value])
            ->where('ended_at', '<=', $now)
            ->update(['event_status_id' => $statuses[EventStatusSlug::FINISHED->value]]);

        $this->info('Estados de eventos actualizados correctamente.');
    }
}
