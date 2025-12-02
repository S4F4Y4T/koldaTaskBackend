<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Policies\V1\ProjectPolicy;
use App\Policies\V1\RolePolicy;
use App\Policies\V1\TaskPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
        Model::unguard();

        // Register policies
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);

        // Register event listeners
        \Event::listen(
            \App\Events\TaskCreated::class,
            \App\Listeners\SendTaskCreatedNotification::class
        );
    }
}
