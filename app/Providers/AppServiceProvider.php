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
                $role = Auth::user()->role;
                $level = $role - 1;

                // Notif untuk user sebagai penerima (misal: atasan)
                $notifs = DB::table('notif')
                    ->where('id_penerima', $userId)
                    ->where('status', 'unread')
                    ->get();

                $notifCount = $notifs->count();
                $verifyCount = DB::table('notif')
                    ->where('notif.status', 'unread')
                    ->where('notif.id_penerima', $userId)
                    ->whereExists(function ($query) use ($userId) {
                        $query->select(DB::raw(1))
                            ->from('request')
                            ->whereColumn('request.id', 'notif.id_request')
                            ->where('request.user_id', '!=', $userId); // Artinya: request bukan milik dia
                    })
                    ->count();




                $PengajuCount = DB::table('notif')
                    ->where('notif.id_penerima', $userId)
                    ->where('notif.status', 'unread')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('request')
                            ->whereColumn('request.id', 'notif.id_request') // Cocokkan ID request
                            ->whereColumn('request.user_id', 'notif.id_penerima'); // Pastikan user sama
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
