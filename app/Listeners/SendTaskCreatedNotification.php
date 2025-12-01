<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Jobs\SendTaskNotification;

class SendTaskCreatedNotification
{
    public function handle(TaskCreated $event): void
    {
        SendTaskNotification::dispatch($event->task);
    }
}
