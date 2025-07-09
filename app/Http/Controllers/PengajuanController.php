<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Support\Str;
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

        // Validasi dasar
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'nilai_kontrak' => 'required|string|max:255',
            'nilai_ajuan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nota' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'rab' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'kwitansi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            // ... tambahkan validasi file lain sesuai kebutuhan
        ]);

        $user = Auth::user();
        $level = $user->role;

        $fileFields = [
            'nota',
            'rab',
            'kwitansi',
            'bukti_nota',          // ⬅️ ganti dari 'faktur'
            'berita_acara',
            'serah_terima',
            'pembayaran',
            'jaminan_garansi',
            'jaminan_pelaksanaan',
            'keputusan',
            'surat_kontrak',
            'surat_perintah',
            'dokumentasi',
            'faktur_pajak',        // ⬅️ ganti dari 'faktur'
            'spp',
            'spm',
            'ssp',
            'sp2d',
            'lain-lain'
        ];


        $uploadedFiles = [];

        foreach ($fileFields as $fieldName) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $fileName = now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('request_uploads');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $fileName);
                $uploadedFiles[$fieldName] = $fileName;
            }
        }

        // Simpan ke tabel request (tanpa kolom file)
        $idRequest = DB::table('request')->insertGetId([
            'user_id' => $user->id,
            'title' => $request->title,
            'desc' => $request->desc,
            'nilai_kontrak' => $request->nilai_kontrak,
            'nilai_ajuan' => $request->nilai_ajuan,
            'type_pengadaan' => $request->type_pengadaan,
            'type' => $request->type,
            'tanggal' => $request->tanggal,
            'level' => $level,
            'status' => 'pending',
            'keterangan' => 'Baru diajukan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan ke tabel request_uploads
        foreach ($uploadedFiles as $field => $filename) {
            DB::table('request_uploads')->insert([
                'id_request' => $idRequest,
                'field' => $field,
                'type' => 1, //1 ajuan 2 revisi
                'file_path' => $filename,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kirim notifikasi
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

        if (!empty($notifData)) {
            DB::table('notif')->insert($notifData);
        }

        if (!empty($targetUserIds)) {
            $this->sendTelegram($message, $targetUserIds);
        }

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil disimpan.');
    }



    public function edit($id)
    {
        $request = DB::table('request')->where('id', $id)->first();
        $uploads = DB::table('request_uploads')->where('id_request', $id)->pluck('file_path', 'field');

        // Gabung dan ubah jadi object
        $request = (object) ((array) $request + $uploads->toArray());

        return view('pengajuan.edit', compact('request'));
    }


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string|max:255',
            'nilai_kontrak' => 'required|string|max:255',
            'nilai_ajuan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'type_pengadaan' => 'required|string|max:100',
            'type' => 'required|string|max:100',

            // Validasi file (semua opsional / nullable)
            'nota' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'rab' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'kwitansi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'bukti_nota' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'berita_acara' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'serah_terima' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'jaminan_garansi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'jaminan_pelaksanaan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'keputusan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'surat_kontrak' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'surat_perintah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'faktur_pajak' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'spp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'spm' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'ssp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'sp2d' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
            'lain-lain' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10058',
        ]);

        $user = Auth::user();

        $fileFields = [
            'nota',
            'rab',
            'kwitansi',
            'bukti_nota',
            'berita_acara',
            'serah_terima',
            'pembayaran',
            'jaminan_garansi',
            'jaminan_pelaksanaan',
            'keputusan',
            'surat_kontrak',
            'surat_perintah',
            'dokumentasi',
            'faktur_pajak',
            'spp',
            'spm',
            'ssp',
            'sp2d',
            'lain-lain'
        ];

        $uploadedFiles = [];

        foreach ($fileFields as $fieldName) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $fileName = now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('request_uploads');

                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $file->move($destination, $fileName);
                $uploadedFiles[$fieldName] = $fileName;
            }
        }

        // Update ke tabel request
        DB::table('request')->where('id', $id)->update([
            'title' => $request->title,
            'desc' => $request->desc,
            'nilai_kontrak' => $request->nilai_kontrak,
            'nilai_ajuan' => $request->nilai_ajuan,
            'type_pengadaan' => $request->type_pengadaan,
            'type' => $request->type,
            'tanggal' => $request->tanggal,
            'updated_at' => now()
        ]);

        // Simpan/Update file ke request_uploads
        foreach ($uploadedFiles as $field => $filename) {
            $existing = DB::table('request_uploads')
                ->where('id_request', $id)
                ->where('field', $field)
                ->first();

            if ($existing) {
                DB::table('request_uploads')->where('id', $existing->id)->update([
                    'file_path' => $filename,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('request_uploads')->insert([
                    'id_request' => $id,
                    'field' => $field,
                    'type' => 1,
                    'file_path' => $filename,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diupdate.');
    }



    public function destroy($id)
    {
        DB::table('request')->where('id', $id)->delete();
        DB::table('request_uploads')->where('id_request', $id)->delete();
        return redirect()->back()->with('success', 'Menghapus Pengajuan !!');
    }
    public function detail($id, Request $request)
    {
        if ($request->has('from')) {
            session(['from_page' => $request->input('from')]);
        }

        $data = DB::table('request')
            ->join('users', 'request.user_id', '=', 'users.id')
            ->where('request.id', $id)
            ->select('request.*', 'users.name as pengaju_name')
            ->first(); // hanya satu

        $fileUploads = DB::table('request_uploads')
            ->select('field', 'file_path')
            ->where('id_request', $id)
            ->whereRaw('id IN (
        SELECT MAX(id) FROM request_uploads 
        WHERE id_request = ? GROUP BY field
    )', [$id])
            ->get()
            ->keyBy('field');

        $approvals = DB::table('approvals')
            ->join('users', 'approvals.user_id', '=', 'users.id')
            ->where('approvals.request_id', $id)
            ->select('approvals.*', 'users.name as approved_by')
            ->orderBy('approvals.approved_at', 'asc')
            ->get();


        return view('pengajuan.detail', compact('data', 'approvals', 'fileUploads'));
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
                        ->orWhere('status', 'approve')
                        ->orWhere('status', 'revisi')
                        ->orWhere('status', 'repair')
                        ->orWhere('level', $level + 1);
                })
                ->select('request.*', 'users.name')
                ->orderBy('tanggal', 'desc')
                ->get();


            foreach ($data as $r) {
                // Ambil riwayat revisi
                $r->riwayat_revisi = DB::table('riwayat_revisi')
                    ->where('id_request', $r->id)
                    ->get();

                // Ambil file uploads
                $r->uploads = DB::table('request_uploads')
                    ->where('id_request', $r->id)
                    ->get()
                    ->keyBy('field'); // supaya bisa diakses seperti $r->uploads['nota']
            }
        } else {

            $data = DB::table('request')
                ->join('users', 'request.user_id', '=', 'users.id')
                ->whereIn('request.level', [$level, $level + 1]) // ambil level sekarang & level atasnya
                ->where('request.level', '<', 4) // pastikan bukan level 4 ke atas
                ->whereIn('request.status', ['approve', 'revisi', 'repair', 'pending'])
                ->select('request.*', 'users.name')
                ->orderBy('request.tanggal', 'desc')
                ->get();


            foreach ($data as $r) {
                // Ambil riwayat revisi
                $r->riwayat_revisi = DB::table('riwayat_revisi')
                    ->where('id_request', $r->id)
                    ->get();

                // Ambil file uploads
                $r->uploads = DB::table('request_uploads')
                    ->where('id_request', $r->id)
                    ->get()
                    ->keyBy('field'); // supaya bisa diakses seperti $r->uploads['nota']
            }
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
        $alreadyNotified = []; // <-- Cegah duplikat

        // Notif ke semua approvals sebelumnya
        foreach ($approvals as $item) {
            if (!in_array($item->user_id, $alreadyNotified)) {
                $notifData[] = [
                    'id_request' => $id,
                    'id_user' => $currentUser->id,
                    'id_penerima' => $item->user_id,
                    'desc' => $notifDesc,
                    'status' => 'unread',
                    'created_at' => now(),
                ];
                $teleUserIds[] = $item->user_id;
                $alreadyNotified[] = $item->user_id;
            }
        }

        // Notif ke pemilik request
        if (!in_array($data->user_id, $alreadyNotified)) {
            $notifData[] = [
                'id_request' => $id,
                'id_user' => $currentUser->id,
                'id_penerima' => $data->user_id,
                'desc' => $notifDesc,
                'status' => 'unread',
                'created_at' => now(),
            ];
            $teleUserIds[] = $data->user_id;
            $alreadyNotified[] = $data->user_id;
        }

        // Notif ke user level selanjutnya (jika belum level 4)
        if ($currentRole < 4) {
            $nextRoleUsers = DB::table('users')->where('role', $currentRole + 1)->get();

            foreach ($nextRoleUsers as $user) {
                if (!in_array($user->id, $alreadyNotified)) {
                    $notifData[] = [
                        'id_request' => $id,
                        'id_user' => $currentUser->id,
                        'id_penerima' => $user->id,
                        'desc' => $notifDesc,
                        'status' => 'unread',
                        'created_at' => now(),
                    ];
                    $teleUserIds[] = $user->id;
                    $alreadyNotified[] = $user->id;
                }
            }
        }

        // Simpan semua notifikasi
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
        $aksi = $request->input('aksi');


        // Perbaikan pada bagian backend
        if ($aksi == 'revisi') {
            // Insert ke approvals
            $notifDesc = 'Pengajuan direvisi oleh ' . $currentUser->name;
            $idApproval = DB::table('approvals')->insertGetId([
                'request_id' => $id,
                'user_id' => $currentUser->id,
                'status' => 'revisi',
                'komentar' => $komentar ?? '-',
                'approved_at' => now(),
                'created_at' => now(),
            ]);


            DB::table('request')->where('id', $id)->update([
                'status' => 'revisi',
                'keterangan' => $notifDesc,
                'level' => $currentRole > 1 ? $currentRole - 1 : 1,
                'updated_at' => now()
            ]);

            // Simpan riwayat revisi per file jika ada
            if ($request->has('revisi_files')) {
                foreach ($request->input('revisi_files') as $fileId => $detail) {
                    if (isset($detail['checked']) && $detail['checked'] === 'on') {
                        $existing = DB::table('riwayat_revisi')
                            ->where('id_request', $id)
                            ->where('id_upload', $fileId) // <- id dari request_uploads
                            ->first();

                        if ($existing) {
                            DB::table('riwayat_revisi')
                                ->where('id', $existing->id)
                                ->update([
                                    'comment' => $detail['komentar'] ?? '-',
                                    'checked' => 1,
                                    'updated_at' => now()
                                ]);
                        } else {
                            DB::table('riwayat_revisi')->insert([
                                'id_approvals' => $idApproval,
                                'id_request' => $id,
                                'id_upload' => $fileId,
                                'is_valid' => 'no',
                                'comment' => $detail['komentar'] ?? '-',
                                'checked' => 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        } else {
            // Rejected
            $notifDesc = 'Pengajuan ditolak oleh ' . $currentUser->name;

            DB::table('approvals')->insert([
                'request_id' => $id,
                'user_id' => $currentUser->id,
                'status' => 'rejected',
                'komentar' => $komentar ?? '-',
                'approved_at' => now(),
                'created_at' => now()
            ]);

            DB::table('request')->where('id', $id)->update([
                'level' => $currentRole,
                'status' => 'rejected',
                'keterangan' => 'Ditolak oleh ' . $currentUser->name,
                'updated_at' => now()
            ]);
        }




        $approvals = DB::table('approvals')
            ->where('request_id', $id)
            ->get();

        $notifData = [];
        $teleUserIds = [];
        $message = $notifDesc;

        if (!$approvals->isEmpty()) {
            $uniquePenerimaIds = $approvals->pluck('user_id')->unique();

            foreach ($uniquePenerimaIds as $penerimaId) {
                // Jangan kirim ke diri sendiri
                if ($penerimaId != $currentUser->id) {
                    $notifData[] = [
                        'id_request'  => $id,
                        'id_user'     => $currentUser->id, // pengirim
                        'id_penerima' => $penerimaId,      // penerima
                        'desc'        => $notifDesc,
                        'status'      => 'unread',
                        'created_at'  => now(),
                    ];

                    $teleUserIds[] = $penerimaId;
                }
            }
        }


        // Insert notifikasi sekaligus
        DB::table('notif')->insert($notifData);

        // Kirim Telegram ke semua pihak
        $this->sendTelegram($message, array_unique($teleUserIds));


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
