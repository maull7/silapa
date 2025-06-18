<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotifController extends Controller
{
    public function read()
    {
        $userId = Auth::user()->id;

        // Ambil semua notif user yang belum dibaca
        $unreadNotifs = DB::table('notif')->where('id_penerima', $userId)->where('status', 'unread')->get();

        // Masukkan ke tabel status_notif
        foreach ($unreadNotifs as $notif) {
            DB::table('notif')->where('id_penerima', $userId)->update([

                'status' => 'read',
                'created_at' => now(),

            ]);
        }

        return redirect()->back()->with('success', 'Membaca Pesan');
    }
}
