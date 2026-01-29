<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        // Untuk route binding atau parameter global (jika dibutuhkan)
        $this->configureBindings();

        // Daftarkan semua routes aplikasi
        $this->routes(function () {
            /**
             * --------------------------
             * ROUTE API
             * --------------------------
             * Semua endpoint REST API diprefix dengan /api
             * dan menggunakan middleware group `api`
             */
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            /**
             * --------------------------
             * ROUTE WEB (Optional)
             * --------------------------
             * Boleh dikosongkan jika kamu full API.
             * Tapi tetap disediakan kalau suatu hari frontend pakai Blade.
             */
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Definisikan route bindings dan pattern global
     */
    protected function configureBindings(): void
    {
        // Contoh: semua parameter {id} hanya angka
        Route::pattern('id', '[0-9]+');
    }
}
