<?php

namespace App\Providers;

use App\Services\Payroll\PayrollCalculateService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PayrollCalculateService::class, function ($app) {
            return new PayrollCalculateService(
                config('common.taxes.mci'),
                config('common.taxes.cpc_coef'),
                config('common.taxes.cmshi_coef'),
                config('common.taxes.mshi_coef'),
                config('common.taxes.sd_coef'),
                config('common.taxes.adjustment_coef'),
                config('common.taxes.iit_coef'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}
