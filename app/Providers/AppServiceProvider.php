<?php

namespace App\Providers;

use App\Models\Category;
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

        // dùng ở menu "Danh mục" trên header và cột "Danh Mục" ở footer
        View::composer('layouts.app', function ($view) {
            $view->with('navCategories', Category::orderBy('id')->get());
        });
    }
}