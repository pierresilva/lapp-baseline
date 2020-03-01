<?php

namespace App\Providers;

error_reporting(E_ALL & ~E_USER_NOTICE);

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
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
        //
        Schema::defaultStringLength(255);
        setlocale(LC_ALL, "es_ES");
        \Carbon\Carbon::setLocale(config('app.locale'));

        // Add Paginate to Collectoin
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate',
                function ($perPage = 10, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage),
                        $this->count(),
                        $perPage,
                        $page,
                        $options
                    ))
                        ->withPath('');
                });
        }

        if (!Collection::hasMacro('recursive')) {
            Collection::macro('recursive', function () {
                return $this->map(function ($value) {
                    if (is_array($value)) {
                        return collect($value)->recursive();
                    }
                    if (is_object($value)) {
                        return collect($value)->recursive();
                    }

                    return $value;
                });
            });
        }
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
