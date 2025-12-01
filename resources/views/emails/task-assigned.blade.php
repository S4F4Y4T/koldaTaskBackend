<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .task-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #4F46E5;
        }

        .detail-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #6b7280;
            display: inline-block;
            width: 120px;
        }

        .value {
            color: #111827;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .status-in_progress {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>New Task Assigned</h1>
    </div>

    <div class="content">
        <p>Hello {{ $assignedUser->name }},</p>

        <p>You have been assigned a new task in the project <strong>{{ $project->title }}</strong>.</p>

        <div class="task-details">
            <h2 style="margin-top: 0; color: #4F46E5;">Task Details</h2>

            <div class="detail-row">
                <span class="label">Task Title:</span>
                <span class="value">{{ $task->title }}</span>
            </div>

            @if($task->description)
                <div class="detail-row">
                    <span class="label">Description:</span>
                    <span class="value">{{ $task->description }}</span>
                </div>
            @endif

            <div class="detail-row">
                <span class="label">Project:</span>
                <span class="value">{{ $project->title }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Client:</span>
                <span class="value">{{ $project->client }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Deadline:</span>
                <span class="value">{{ $task->deadline->format('F j, Y \a\t g:i A') }}</span>
            </div>

            <div class="detail-row">
                <span class="label">Status:</span>
                <span class="value">
                    <span class="status-badge status-{{ $task->status->value }}">
                        {{ $task->status->label() }}
                    </span>
                </span>
            </div>
        </div>

        <p>Please review the task details and start working on it at your earliest convenience.</p>

        <div style="text-align: center;">
            <a href="{{ config('app.url') }}/tasks/{{ $task->id }}" class="button">
                View Task
            </a>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>If you have any questions, please contact your project manager.</p>
    </div>
</body>

</html>