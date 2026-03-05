<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(function ($router) {
                require base_path('routes/onlinePay.php');
                require base_path('routes/adminPanel.php');
                require base_path('routes/ads.php');
                require base_path('routes/barbershop.php');
                require base_path('routes/takeaway.php');
                require base_path('routes/delivery.php');
                require base_path('routes/web.php');
                require base_path('routes/superadmin.php');
                require base_path('routes/saContract.php');
                require base_path('routes/profile.php');
                require base_path('routes/admToSaMsg.php');
                require base_path('routes/userAdmin.php');
                require base_path('routes/adminMngWorkers.php');
                require base_path('routes/LPages.php');
                require base_path('routes/monthlyRechnungCl.php');
                require base_path('routes/ordersForTATemp.php');
                require base_path('routes/stockMng.php');
                require base_path('routes/giftCard.php');
            });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
