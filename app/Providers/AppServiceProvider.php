<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();
        Carbon::setLocale('id');

        View::composer('layouts.navigation', function ($view) {
            $view->with('activeNavCategories', Category::active()->orderBy('id')->get());
        });
    }
}
