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
                $userId = Auth::user()->id;

                // Notif untuk user sebagai penerima (misal: atasan)
                $notifs = DB::table('notif')
                    ->where('id_penerima', $userId)
                    ->where('status', 'unread')
                    ->get();

                $notifCount = $notifs->count();
                $verifyCount = DB::table('notif')
                    ->leftJoin('request', 'request.user_id', '=', 'notif.id_penerima')
                    ->whereNull('request.user_id') // berarti notif.id_penerima gak ditemukan di request.user_id
                    ->where('notif.status', 'unread')
                    ->where('notif.id_penerima', $userId) // tetap filter sesuai user login
                    ->count();


                $PengajuCount = DB::table('notif')
                    ->where('notif.id_penerima', $userId)
                    ->where('notif.status', 'unread')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('request')
                            ->whereColumn('request.user_id', 'notif.id_penerima');
                    })
                    ->count();




                $view->with(compact(
                    'notifCount',
                    'notifs',
                    'verifyCount',
                    'PengajuCount'

                ));
            }
        });
    }
}
