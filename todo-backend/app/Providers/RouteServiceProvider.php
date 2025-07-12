<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Définir les routes pour l'application.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Routes API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Routes web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
