<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kasbon;
use App\Observers\KasbonObserver;
use App\Models\Reimbursement;
use App\Observers\ReimbursementObserver;
use App\Models\Payroll;
use App\Observers\PayrollObserver;

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
        Kasbon::observe(KasbonObserver::class);
        Reimbursement::observe(ReimbursementObserver::class);
        date_default_timezone_set('Asia/Jakarta');

    }
}
