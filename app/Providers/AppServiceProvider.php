<?php

namespace App\Providers;

use App\Models\{Championship, Permission, Role, User};
use App\Policies\{ChampionshipPolicy, PermissionPolicy, RolePolicy, UserPolicy};
use Illuminate\Support\Facades\{Blade, Gate, Log, URL};
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

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
        // Debug da rota de upload do Livewire
        Request::macro('hasValidSignature', function ($absolute = true) {
            /** @var Request $this */
            $isValid = \Illuminate\Support\Facades\URL::hasValidSignature($this, $absolute);

            if (!$isValid && str_contains($this->path(), 'livewire/upload-file')) {
                Log::error('DEBUG LIVEWIRE UPLOAD SIGNATURE FAILED:', [
                    'request_full_url' => $this->fullUrl(),
                    'request_scheme' => $this->getScheme(),
                    'request_host' => $this->getHost(),
                    'request_port' => $this->getPort(),
                    'x_forwarded_proto' => $this->header('X-Forwarded-Proto'),
                    'x_forwarded_host' => $this->header('X-Forwarded-Host'),
                    'x_forwarded_port' => $this->header('X-Forwarded-Port'),
                    'cf_visitor' => $this->header('CF-Visitor'),
                    'app_url_config' => config('app.url'),
                ]);
            }

            return $isValid;
        });

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Championship::class, ChampionshipPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        if (app()->environment('production')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        Blade::directive('datetime', function ($expression) {
            return "<?php echo  \Illuminate\Support\Carbon::parse($expression)->format('d/m/Y \à\s H:i'); ?>";
        });
    }

}