<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Jobs\SendTaskNotification;

/**
 * Listener for TaskCreated event
 * 
 * Dispatches the queue job to send email notification
 * when a task is created.
 */
class SendTaskCreatedNotification
{
    /**
     * Handle the event.
     *
     * @param TaskCreated $event
     * @return void
     */
    public function handle(TaskCreated $event): void
    {
        // Dispatch the queue job to send email
        SendTaskNotification::dispatch($event->task);
    }
}
