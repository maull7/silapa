<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
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
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $notifs = DB::table('notif')
                    ->whereNot('id_user', Auth::id()) // notif untuk user ini
                    ->whereNotIn('id', function ($query) {
                        $query->select('id_notif')
                            ->from('status_notif')
                            ->where('id_user', Auth::id());
                    })
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get();

                $notifCount = $notifs->count();


                $view->with(compact('notifCount', 'notifs'));
            }
        });
    }
}
