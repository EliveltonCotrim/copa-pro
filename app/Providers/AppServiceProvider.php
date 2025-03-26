<?php

namespace App\Providers;

use App\Models\Championship;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\ChampionshipPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Blade;
use Filament\Facades\Filament;
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
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Championship::class, ChampionshipPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        Filament::registerRenderHook(
            'panels::auth.login.form.after',
            fn () => Blade::render('@Vite(\'resources/css/custom-login.css\')'),
        );
    }
}
