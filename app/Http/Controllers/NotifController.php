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
        $unreadNotifs = DB::table('notif')
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('id_notif')
                    ->from('status_notif')
                    ->where('id_user', $userId);
            })
            ->get();

        // Masukkan ke tabel status_notif
        foreach ($unreadNotifs as $notif) {
            DB::table('status_notif')->insert([
                'id_notif' => $notif->id,
                'id_user' => $userId,
                'status' => 'read',
                'created_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Membaca Pesan');
    }
}
