<?php

namespace App\Providers;

use App\Http\ViewComposers\UserFieldsComposer;
use Illuminate\Support\Facades\{Schema,View};
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer(['user.create', 'user.edit'], UserFieldsComposer::class);
    }
}
