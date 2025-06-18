<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        // if (config('app.env') === 'local') {
        //     URL::forceScheme('https');
        // }
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $notifs = DB::table('notif')
                    ->where('id_penerima', Auth::user()->id)
                    ->where('status', 'unread')
                    ->get();

                $notifCount = $notifs->count();


                $view->with(compact('notifCount', 'notifs'));
            }
        });
    }
}
