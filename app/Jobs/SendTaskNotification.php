<?php

namespace App\Jobs;

use App\Mail\TaskAssignedMail;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Queue job to send task assignment notification email
 * 
 * This job is dispatched when a task is created and sends
 * an email notification to the assigned user.
 */
class SendTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     *
     * @param Task $task
     */
    public function __construct(
        public Task $task
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // Load relationships if not already loaded
        if (!$this->task->relationLoaded('assignedUser')) {
            $this->task->load('assignedUser');
        }

        if (!$this->task->relationLoaded('project')) {
            $this->task->load('project');
        }

        // Send email to assigned user
        Mail::to($this->task->assignedUser->email)
            ->send(new TaskAssignedMail($this->task));
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        // Log the failure or notify administrators
        \Log::error('Failed to send task notification', [
            'task_id' => $this->task->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
