<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\SiteSetting;
use App\Listeners\LogSentEmail;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;

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
        Event::listen(MessageSent::class, LogSentEmail::class);

        View::composer(['layouts.app', 'home.*', 'companies.*', 'compare.*', 'quote.*'], function ($view) {
            try {
                $siteLogo = Cache::remember('site_logo', 3600, fn() => SiteSetting::get('site_logo'));
                $siteName = Cache::remember('site_name', 3600, fn() => SiteSetting::get('site_name', 'オヤズナ'));
                
                $view->with([
                    'siteLogo' => $siteLogo,
                    'siteName' => $siteName,
                ]);
            } catch (\Exception $e) {
                $view->with([
                    'siteLogo' => null,
                    'siteName' => 'オヤズナ',
                ]);
            }
        });
    }
}
