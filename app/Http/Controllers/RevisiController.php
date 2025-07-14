<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class RevisiController extends Controller
{
    public function revisi($id, Request $request)
    {
        if ($request->has('from')) {
            session(['from_page' => $request->input('from')]);
        }

        // Ambil data request utama
        $requestData = DB::table('request')->find($id);

        // Ambil data revisi yang masih perlu dikerjakan (checked = 1)
        $revisiFiles = DB::table('riwayat_revisi')
            ->where('id_request', $id)
            ->where('checked', 1)
            ->get();

        // Ambil file dari request_uploads berdasarkan ID yang direvisi
        $uploadIds = $revisiFiles->pluck('id_upload')->toArray();

        $uploads = DB::table('request_uploads')
            ->whereIn('id', $uploadIds)
            ->get()
            ->keyBy('id'); // ← GANTI DI SINI

        // Gabungkan revisi dengan detail file upload
        $revisiFiles = $revisiFiles->map(function ($item) use ($uploads) {
            $upload = $uploads[$item->id_upload] ?? null; // ← PAKAI id_upload, bukan item->id

            return [
                'upload_id' => $item->id_upload,
                'file_path' => $upload?->file_path,
                'field' => $upload?->field,
                'comment' => $item->comment,
            ];
        });


        $data = [
            'request' => $requestData,
            'revisi_files' => $revisiFiles,
        ];

        return view('revisi.revisi', compact('data'));
    }


    public function revisiStore(Request $request)
    {

        $request->validate([
    'nota' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'rab' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'kwitansi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'bukti_nota' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'berita_acara' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'serah_terima' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'jaminan_garansi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'jaminan_pelaksanaan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'keputusan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'surat_kontrak' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'surat_perintah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'faktur_pajak' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'spp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'spm' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'ssp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'sp2d' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    'lain-lain' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
], [
    // Pesan Bahasa Indonesia
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'max' => ':attribute tidak boleh lebih dari :max karakter.',
    'file' => ':attribute harus berupa file.',
    'mimes' => ':attribute harus berupa file dengan format: :values.',
    'date' => ':attribute harus berupa tanggal yang valid.',
    'nota.max' => 'Ukuran file Nota tidak boleh lebih dari 10MB.',
    'rab.max' => 'Ukuran file RAB tidak boleh lebih dari 10MB.',
    'kwitansi.max' => 'Ukuran file Kwitansi tidak boleh lebih dari 10MB.',
    'bukti_nota.max' => 'Ukuran file Bukti Nota tidak boleh lebih dari 10MB.',
    'berita_acara.max' => 'Ukuran file Berita Acara tidak boleh lebih dari 10MB.',
    'serah_terima.max' => 'Ukuran file Serah Terima tidak boleh lebih dari 10MB.',
    'pembayaran.max' => 'Ukuran file Pembayaran tidak boleh lebih dari 10MB.',
    'jaminan_garansi.max' => 'Ukuran file Jaminan Garansi tidak boleh lebih dari 10MB.',
    'jaminan_pelaksanaan.max' => 'Ukuran file Jaminan Pelaksanaan tidak boleh lebih dari 10MB.',
    'keputusan.max' => 'Ukuran file Keputusan tidak boleh lebih dari 10MB.',
    'surat_kontrak.max' => 'Ukuran file Surat Kontrak tidak boleh lebih dari 10MB.',
    'surat_perintah.max' => 'Ukuran file Surat Perintah tidak boleh lebih dari 10MB.',
    'dokumentasi.max' => 'Ukuran file Dokumentasi tidak boleh lebih dari 10MB.',
    'faktur_pajak.max' => 'Ukuran file Faktur Pajak tidak boleh lebih dari 10MB.',
    'spp.max' => 'Ukuran file SPP tidak boleh lebih dari 10MB.',
    'spm.max' => 'Ukuran file SPM tidak boleh lebih dari 10MB.',
    'ssp.max' => 'Ukuran file SSP tidak boleh lebih dari 10MB.',
    'sp2d.max' => 'Ukuran file SP2D tidak boleh lebih dari 10MB.',
    'lain-lain.max' => 'Ukuran file Lain-lain tidak boleh lebih dari 10MB.',
]);

        $currentUser = Auth::user();
        $requestId = $request->input('id');
        $requestData = DB::table('request')->where('id', $requestId)->first();

        if (!$requestData) {
            return redirect()->back()->with('error', 'Data request tidak ditemukan.');
        }

        $notifDesc = 'Pengajuan telah diperbaiki oleh ' . $currentUser->name;

        // Ambil semua ID upload yang perlu direvisi
        $revisiList = DB::table('riwayat_revisi')
            ->where('id_request', $requestId)
            ->where('checked', 1)
            ->pluck('id_upload')
            ->toArray();

        $totalRevisi = count($revisiList);
        $revisedUploadIds = [];

        // Proses upload file yang sesuai daftar revisi
        foreach ($request->files as $field => $file) {
            if ($request->hasFile($field)) {
                $upload = DB::table('request_uploads')
                    ->where('id_request', $requestId)
                    ->where('field', $field)
                    ->orderByDesc('id')
                    ->first();

                if ($upload && in_array($upload->id, $revisiList)) {
                    // Hapus file lama
                    $oldFilePath = public_path('request_uploads/' . $upload->file_path);
                    if (File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }

                    // Simpan file baru
                    $ext = $file->getClientOriginalExtension();
                    $newFileName = now()->format('Ymd_His') . '_' . Str::random(10) . '.' . $ext;
                    $file->move(public_path('request_uploads'), $newFileName);

                    // Update upload
                    DB::table('request_uploads')->where('id', $upload->id)->update([
                        'file_path' => $newFileName,
                        'updated_at' => now()
                    ]);

                    $revisedUploadIds[] = $upload->id;
                }
            }
        }

        // ✅ Cek apakah SEMUA file revisi sudah diperbaiki
        $jumlahSelesai = count($revisedUploadIds);
        $statusUpdated = false;

        if ($jumlahSelesai === $totalRevisi && $jumlahSelesai > 0) {
            // Ubah status request
            DB::table('request')->where('id', $requestId)->update([
                'status' => 'repair',
                'keterangan' => $notifDesc,
                'updated_at' => now()
            ]);

            // Update status di riwayat_revisi
            DB::table('riwayat_revisi')
                ->where('id_request', $requestId)
                ->whereIn('id_upload', $revisedUploadIds)
                ->update([
                    'checked' => 2,
                    'updated_at' => now()
                ]);

            // Notifikasi
            $approvals = DB::table('approvals')->where('request_id', $requestId)->get();
            $receiverIds = $approvals->pluck('user_id')->toArray();
            $receiverIds[] = $requestData->user_id;
            $receiverIds = array_unique(array_filter($receiverIds));

            $notifData = [];
            foreach ($receiverIds as $receiverId) {
                if ($receiverId != $currentUser->id) {
                    $notifData[] = [
                        'id_request' => $requestId,
                        'id_user' => $currentUser->id,
                        'id_penerima' => $receiverId,
                        'desc' => $notifDesc,
                        'status' => 'unread',
                        'created_at' => now()
                    ];
                }
            }

            if (!empty($notifData)) {
                DB::table('notif')->insert($notifData);
            }

            // Kirim ke Telegram
            if (method_exists($this, 'sendTelegram')) {
                $this->sendTelegram($notifDesc, $receiverIds);
            }

            $statusUpdated = true;
        } else {
            // Belum semua file direvisi
            if ($jumlahSelesai > 0) {
                // Tetap update file upload & riwayat revisi, tapi tanpa ubah status request
                DB::table('riwayat_revisi')
                    ->where('id_request', $requestId)
                    ->whereIn('id_upload', $revisedUploadIds)
                    ->update([
                        'checked' => 2,
                        'updated_at' => now()
                    ]);
            }
        }

        $from = session('from_page');
        session()->forget('from_page');

        $message = $statusUpdated
            ? 'Revisi berhasil disimpan.'
            : 'Sebagian revisi disimpan. Masih ada file yang belum diperbaiki.';

        return match ($from) {
            'verifikasi-pengajuan' => redirect()->route('pengajuan.verify')->with('success', $message),
            'pengajuan' => redirect()->route('pengajuan.index')->with('success', $message),
            default => redirect()->route('dashboard')->with('success', $message)
        };
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
