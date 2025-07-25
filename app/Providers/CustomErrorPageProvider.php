<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class CustomErrorPageProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // 403
        Response::macro('forbidden', function () {
            return response()->view('errors.403', [], 403);
        });
        // 404
        Response::macro('notFound', function () {
            return response()->view('errors.404', [], 404);
        });
    }
}
