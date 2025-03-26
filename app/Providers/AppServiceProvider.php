<?php

namespace App\Providers;

use App\Models\{Championship, Permission, Role, User};
use App\Policies\{ChampionshipPolicy, PermissionPolicy, RolePolicy, UserPolicy};
use Illuminate\Auth\Notifications\{ResetPassword, VerifyEmail};
use Illuminate\Support\Facades\Blade;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use Illuminate\Notifications\Messages\MailMessage;
use Vite;

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

        VerifyEmail::toMailUsing(function($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifique seu e-mail')
                ->line('Por favor, clique no link abaixo para verificar seu e-mail.')
                ->action('Verificar e-mail', $url)
                ->line('Se você não criou uma conta, nenhuma ação é requerida.')
                ->salutation("Atenciosamente,\n\nCopa Pro");
        });

        ResetPassword::toMailUsing(function($notifiable, $url) {
            $expires = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
            $primeiro_nome = explode(' ', trim($notifiable->name))[0];
            return (new MailMessage)
                ->greeting("Olá, $primeiro_nome!")
                ->subject('Notificação para resetar senha')
                ->line('Se você está recebendo esse e-mail, é por que recebemos um pedido de redefinição de senha para sua conta.')
                ->action('Resetar senha', $url)
                ->line("Este link de reset de senha vai expirar em $expires minutos.")
                ->line('Se você não requisitou o reset de senha, ignore essa mensagem.')
                ->salutation("Atenciosamente,\n\nCopa Pro");
        });
    }

}