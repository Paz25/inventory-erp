<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\IgrUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application servicesw.
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
        Blade::if('role', fn(string ...$roles) => in_array(auth()->user()?->role, $roles, true));

        Auth::provider('igr', function ($app, array $config) {
            return new IgrUserProvider(
                $app['db']->connection($config['connection']),
                $app['hash'],
                $config['table']
            );
        });

        View::composer('partials.sidebar', function ($view) {
            $menus = collect(config('menu'))
                ->filter(fn($menu) => is_null($menu['roles']) || in_array(auth()->user()?->role, $menu['roles'], true))
                ->values();

            $view->with('menus', $menus);
        });
    }
}
