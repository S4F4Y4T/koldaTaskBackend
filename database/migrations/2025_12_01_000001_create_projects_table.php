<?php

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('client');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ProjectStatus::values())->default(ProjectStatus::PENDING->value);
            $table->timestamps();

            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
