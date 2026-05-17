<?php

namespace App\Modules\Events\Actions\EventActivities;

use App\Models\Events\EventActivity;
use App\Modules\Events\DTOs\EventActivity\DraftEventActivityFormData;
use Illuminate\Support\Facades\DB;

class UpdateEventActivityAction
{
    /**
     * Execute the action.
     */
    public function execute(EventActivity $activity, DraftEventActivityFormData $data): EventActivity
    {
        return DB::transaction(function () use ($activity) {

            return $activity;
        });
    }
}
