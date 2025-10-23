<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

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
        Schema::defaultStringLength(191);

        // Usar Bootstrap 5 para la paginación en lugar de Tailwind
        Paginator::useBootstrapFive();

        // Configurar Carbon para usar español en todas las fechas
        Carbon::setLocale('es');
    }
}
