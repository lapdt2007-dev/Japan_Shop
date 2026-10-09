<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Cấp biến $cartCount cho tất cả các giao diện
        View::composer('*', function ($view) {
            $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
            $view->with('cartCount', $cartCount);
        });
    }
}
