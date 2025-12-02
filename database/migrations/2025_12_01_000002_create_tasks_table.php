<?php

use App\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('deadline');
            $table->foreignId('assigned_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', TaskStatus::values())->default(TaskStatus::PENDING->value);
            $table->timestamps();

            // Indexes for better query performance
            $table->index('project_id');
            $table->index('assigned_user_id');
            $table->index('status');
            $table->index('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
