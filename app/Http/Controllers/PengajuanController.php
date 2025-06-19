<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PengajuanController extends Controller
{
    public function index()
    {
        $data = DB::table('request')
            ->join('users', 'request.user_id', '=', 'users.id')
            ->where('users.id', auth()->id())
            ->select('request.*', 'users.name')
            ->get();

        return view('pengajuan.index', compact('data'));
    }
    public function create()
    {
        return view('pengajuan.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fileName = null;

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('bukti_pengajuan');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
        }

        $user = Auth::user();
        $level = $user->role;

        $idRequest = DB::table('request')->insertGetId([
            'user_id' => $user->id,
            'title' => $request->title,
            'desc' => $request->desc,
            'bukti' => $fileName,
            'level' => $level,
            'status' => 'pending',
            'keterangan' => 'Baru diajukan',
            'tanggal' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $notifDesc = 'Pengajuan dilakukan ' . $user->name;
        $message = $user->name . ' Telah Melakukan Pengajuan';

        $notifData = [];
        $targetUserIds = [];

        if ($level == 1) {
            $to = DB::table('map')->where('id_child', $user->id)->first();

            if ($to) {
                $notifData[] = [
                    'id_request' => $idRequest,
                    'id_user' => $user->id,
                    'id_penerima' => $to->id_parent,
                    'desc' => $notifDesc,
                    'status' => 'unread',
                    'created_at' => now(),
                ];
                $targetUserIds[] = $to->id_parent;
            }
        } else {
            $nextRole = $level + 1;
            $usersTo = DB::table('users')->where('role', $nextRole)->get();

            foreach ($usersTo as $u) {
                $notifData[] = [
                    'id_request' => $idRequest,
                    'id_user' => $user->id,
                    'id_penerima' => $u->id,
                    'desc' => $notifDesc,
                    'status' => 'unread',
                    'created_at' => now(),
                ];
                $targetUserIds[] = $u->id;
            }
        }

        // Insert semua notifikasi sekaligus
        if (!empty($notifData)) {
            DB::table('notif')->insert($notifData);
        }

        // Kirim Telegram sekali untuk semua target
        if (!empty($targetUserIds)) {
            $this->sendTelegram($message, $targetUserIds);
        }

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil disimpan.');
    }


    public function edit($id)
    {
        $data = DB::table('request')->where('id', $id)->first();
        return view('pengajuan.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('bukti_pengajuan'); // folder lebih rapi & jelas

            // Buat folder kalau belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            // Simpan path-nya ke database
            $path = 'bukti_pengajuan/' . $fileName;
        }

        $level = Auth::user()->role;

        // Insert data ke database
        DB::table('request')->where('id', $id)->update([
            'user_id' => auth()->id(),          // atau sesuai logika user
            'title' => $request->title,
            'desc' => $request->desc,
            'bukti' => $fileName,
            'level' => $level,                      // default level misal
            'status' => 'Menunggu Persetujuan Bos 1',             // default status
            'keterangan' => 'Baru diajukan',               // bisa juga null jika opsional
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diubah.');
    }

    public function destroy($id)
    {
        DB::table('request')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Menghapus Pengajuan !!');
    }
    public function detail($id)
    {

        $data = DB::table('request')
            ->join('users', 'request.user_id', '=', 'users.id')
            ->where('request.id', $id)
            ->select('request.*', 'users.name as pengaju_name')
            ->first(); // hanya satu

        $approvals = DB::table('approvals')
            ->join('users', 'approvals.user_id', '=', 'users.id')
            ->where('approvals.request_id', $id)
            ->select('approvals.*', 'users.name as approved_by')
            ->orderBy('approvals.approved_at', 'asc')
            ->get();


        return view('pengajuan.detail', compact('data', 'approvals'));
    }
    public function verify()
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


        $role = Auth::user()->role;
        $level = $role - 1;

        if ($role == 2) {
            // Ambil ID murid yang terkait dengan wali kelas ini dari tabel map
            $muridIds = DB::table('map')
                ->where('id_parent', Auth::id())
                ->pluck('id_child');

            $data = DB::table('request')
                ->join('users', 'request.user_id', '=', 'users.id')
                ->whereIn('request.user_id', $muridIds) // hanya murid yang di bawahnya
                ->where('level', '<', 4) // jangan tampilkan yang sudah sampai level 4
                ->where(function ($query) use ($level) {
                    $query->where('level', $level)
                        ->orWhere('status', 'approve');
                })
                ->select('request.*', 'users.name')
                ->orderBy('tanggal', 'desc')
                ->get();
        } else {

            $data = DB::table('request')
                ->join('users', 'request.user_id', '=', 'users.id')
                ->where('level', '<', 4)
                ->where(function ($query) use ($level) {
                    $query->where('level', $level)
                        ->orWhere('status', 'approve');
                })
                ->select('request.*', 'users.name')
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('pengajuan.verify', compact('data'));
    }


    public function approve(Request $request, $id)
    {
        $data = DB::table('request')->where('id', $id)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $approvals = DB::table('approvals')->where('request_id', $id)->get();
        $currentUser = Auth::user();
        $currentRole = $currentUser->role;
        $komentar = $request->input('komentar');

        // Update status request
        $updateData = [
            'status' => 'approve',
            'level' => $currentRole >= 4 ? 4 : $currentRole,
            'keterangan' => ($currentRole >= 4 ? 'Disetujui final oleh ' : 'Disetujui oleh ') . $currentUser->name,
            'updated_at' => now()
        ];
        DB::table('request')->where('id', $id)->update($updateData);

        // Siapkan notifikasi
        $notifDesc = 'Pengajuan disetujui oleh ' . $currentUser->name;
        $message = $notifDesc;
        $notifData = [];
        $teleUserIds = [];

        // Tambahkan notifikasi ke semua approvals sebelumnya
        foreach ($approvals as $item) {
            $notifData[] = [
                'id_request' => $id,
                'id_user' => $currentUser->id,
                'id_penerima' => $item->user_id,
                'desc' => $notifDesc,
                'status' => 'unread',
                'created_at' => now(),
            ];
            $teleUserIds[] = $item->user_id;
        }

        // Notif ke pemilik request
        $notifData[] = [
            'id_request' => $id,
            'id_user' => $currentUser->id,
            'id_penerima' => $data->user_id,
            'desc' => $notifDesc,
            'status' => 'unread',
            'created_at' => now(),
        ];
        $teleUserIds[] = $data->user_id;

        // Kirim ke user level selanjutnya (jika belum level 4)
        if ($currentRole < 4) {
            $nextRoleUsers = DB::table('users')->where('role', $currentRole + 1)->get();

            foreach ($nextRoleUsers as $user) {
                $notifData[] = [
                    'id_request' => $id,
                    'id_user' => $currentUser->id,
                    'id_penerima' => $user->id,
                    'desc' => $notifDesc,
                    'status' => 'unread',
                    'created_at' => now(),
                ];
                $teleUserIds[] = $user->id;
            }
        }

        // Insert notifikasi sekaligus
        DB::table('notif')->insert($notifData);

        // Kirim Telegram
        $this->sendTelegram($message, array_unique($teleUserIds));

        // Simpan approval komentar
        DB::table('approvals')->insert([
            'request_id' => $id,
            'user_id' => $currentUser->id,
            'status' => 'approve',
            'komentar' => $komentar,
            'approved_at' => now(),
            'created_at' => now()
        ]);

        // Kirim notifikasi komentar ke pemilik
        DB::table('notif')->insert([
            'id_request' => $id,
            'id_user' => $currentUser->id,
            'id_penerima' => $data->user_id,
            'desc' => $currentUser->name . ' telah mengomentari pengajuan Anda',
            'status' => 'unread',
            'created_at' => now(),
        ]);

        // (Opsional) kirim telegram untuk komentar juga
        $this->sendTelegram($currentUser->name . ' telah mengomentari pengajuan Anda', $data->user_id);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }


    public function reject(Request $request, $id)
    {
        $data = DB::table('request')->where('id', $id)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $currentUser = Auth::user();
        $currentRole = $currentUser->role;
        $komentar = $request->input('komentar');

        // Update status request menjadi ditolak
        DB::table('request')->where('id', $id)->update([
            'level' => $currentRole,
            'status' => 'rejected',
            'keterangan' => 'Ditolak oleh ' . $currentUser->name,
            'updated_at' => now()
        ]);

        $approvals = DB::table('approvals')->where('request_id', $id)->get();
        $notifData = [];
        $teleUserIds = [];

        $notifDesc = 'Pengajuan ditolak oleh ' . $currentUser->name;
        $message = $notifDesc;

        if (!$approvals->isEmpty()) {
            foreach ($approvals as $item) {
                $notifData[] = [
                    'id_request' => $id,
                    'id_user' => $currentUser->id,
                    'id_penerima' => $item->user_id,
                    'desc' => $notifDesc,
                    'status' => 'unread',
                    'created_at' => now(),
                ];
                $teleUserIds[] = $item->user_id;
            }
        }

        // Tambah notifikasi ke pemilik request
        $notifData[] = [
            'id_request' => $id,
            'id_user' => $currentUser->id,
            'id_penerima' => $data->user_id,
            'desc' => $notifDesc,
            'status' => 'unread',
            'created_at' => now(),
        ];
        $teleUserIds[] = $data->user_id;

        // Insert notifikasi sekaligus
        DB::table('notif')->insert($notifData);

        // Kirim Telegram ke semua pihak
        $this->sendTelegram($message, array_unique($teleUserIds));

        // Simpan approval tolak + komentar
        DB::table('approvals')->insert([
            'request_id' => $id,
            'user_id' => $currentUser->id,
            'status' => 'rejected',
            'komentar' => $komentar,
            'approved_at' => now(),
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }



    public function success()
    {
        $data = DB::table('request')
            ->join('users', 'request.user_id', '=', 'users.id')
            ->where('level', 4)
            ->where('status', 'approve')
            ->select('request.*', 'users.name')
            ->get();
        return view('pengajuan.approve', compact('data'));
    }

    public function cancel()
    {
        $data = DB::table('request')
            ->join('users', 'request.user_id', '=', 'users.id')
            ->where('status', 'rejected')
            ->select('request.*', 'users.name')
            ->get();
        return view('pengajuan.reject', compact('data'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:500',
        ]);

        $data = DB::table('approvals')->where('id_approvals', $id)->first();

        DB::table('reply')->insert([
            'approvals_id' => $id,
            'user_id' => Auth::id(),
            'parent_id' => $data->user_id,
            'komentar' => $request->komentar,
            'created_at' => now()
        ]);

        DB::table('notif')->insert([
            'id_request' => $data->request_id,
            'id_user' => Auth::id(),
            'id_penerima' => $data->user_id,
            'desc' => Auth::user()->name . ' telah mengomentari pengajuan Anda',
            'status' => 'unread',
            'created_at' => now(),
        ]);

        $this->sendTelegram(Auth::user()->name . ' telah mengomentari pengajuan Anda', $data->user_id);

        return redirect()->back()->with('success', 'Komentar berhasil dikirim.');
    }

    public function replyComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:500',
            'parent_id' => 'required|integer' // ini ID reply yang dibalas
        ]);

        $data = DB::table('approvals')->where('id_approvals', $id)->first();

        DB::table('reply')->insert([
            'approvals_id' => $id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'komentar' => $request->komentar,
            'created_at' => now()
        ]);

        // Kirim notif & telegram ke pemilik approval kalau bukan diri sendiri
        if ($request->parent_id != Auth::id()) {
            DB::table('notif')->insert([
                'id_request' => $data->request_id,
                'id_user' => Auth::id(),
                'id_penerima' => $request->parent_id,
                'desc' => Auth::user()->name . ' telah membalas komentar Anda',
                'status' => 'unread',
                'created_at' => now(),
            ]);

            $this->sendTelegram(Auth::user()->name . ' telah membalas komentar Anda', $request->parent_id);
        }

        return redirect()->back()->with('success', 'Balasan komentar berhasil dikirim.');
    }


    public function sendTelegram($message, $userIds)
    {
        if (!is_array($userIds)) {
            $userIds = [$userIds]; // biar fleksibel, bisa kirim 1 atau banyak
        }

        $bots = DB::table('tele_bot')
            ->whereIn('user_id', $userIds)
            ->select('chat_id')
            ->distinct()
            ->get();

        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot$token/sendMessage";

        $allSuccess = true;

        foreach ($bots as $bot) {
            $response = Http::get($url, [
                'chat_id' => $bot->chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if (!$response->successful()) {
                Log::error("Gagal kirim pesan ke chat_id {$bot->chat_id}: " . $response->body());
                $allSuccess = false;
            }
        }

        return $allSuccess;
    }
}
