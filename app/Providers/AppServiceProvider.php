<?php

namespace App\Providers;

use App\Models\ItemStock;
use App\Models\Role;
use App\Models\User;
use App\Observers\StockObserver;
use App\Policies\V1\RolePolicy;
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
        Model::shouldBeStrict(!$this->app->isProduction());
        Model::unguard();

        // Register policies
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, \App\Policies\V1\UserPolicy::class);
        Gate::policy(\App\Models\Project::class, \App\Policies\ProjectPolicy::class);
        Gate::policy(\App\Models\Task::class, \App\Policies\TaskPolicy::class);

        // Register event listeners
        \Event::listen(
            \App\Events\TaskCreated::class,
            \App\Listeners\SendTaskCreatedNotification::class
        );
    }
}
