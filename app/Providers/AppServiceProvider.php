<?php

namespace App\Providers;

use Illuminate\Support\Facades\{Schema,View,Blade};
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

        Blade::directive('render', function($expression){
            $parts = explode(',', $expression, 2);

            $component = $parts[0];
            $args = trim($parts[1] ?? '[]');

            return "<?php echo app('App\Http\ViewComponents\\\\'.{$component}, {$args})->toHtml(); ?>";
        });
    }
}
