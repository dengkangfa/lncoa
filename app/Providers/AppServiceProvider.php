<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \DB::listen(function ($query) {
            \Log::info($query->sql);
            // $query->bindings
            // $query->time
        });
        $lang = config('app.locale') != 'zh_cn' ? config('app.locale') : 'zh';
        \Carbon\Carbon::setLocale($lang);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
