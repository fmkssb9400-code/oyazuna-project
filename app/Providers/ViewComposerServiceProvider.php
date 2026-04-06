<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Company;

class ViewComposerServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        // ヘッダーに会社数を表示するためのView Composer
        View::composer('layouts.app', function ($view) {
            $companyCount = Company::count();
            $view->with('companyCount', $companyCount);
        });
    }
}
