<?php

namespace App\Providers;

use App\Models\{Championship, Permission, Role, User};
use App\Policies\{ChampionshipPolicy, PermissionPolicy, RolePolicy, UserPolicy};
use Illuminate\Support\Facades\{Blade, Gate, URL};
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
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Championship::class, ChampionshipPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Blade::directive('datetime', function ($expression) {
            return "<?php echo  \Illuminate\Support\Carbon::parse($expression)->format('d/m/Y \à\s H:i'); ?>";
        });
    }

}