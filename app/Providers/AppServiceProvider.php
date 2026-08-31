<?php

namespace App\Providers;

use App\Models\Product;
use App\Policies\ProductPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

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
        Gate::policy(Product::class, ProductPolicy::class);

        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        $this->configureDefaults();

        // Marque la requête suivante comme "juste après connexion" (particuliers
        // uniquement) : ça permet au JS de forcer l'adresse du compte comme
        // position de recherche, même si une autre position traînait déjà.
        Event::listen(Login::class, function (Login $event): void {
            if ($event->guard === 'web') {
                session()->flash('just_logged_in', true);
            }
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
