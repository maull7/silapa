<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PDO;

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

        // Mapping role → level
        $level = Auth::user()->role;

        $id = DB::table('request')->insertGetId([
            'user_id' => auth()->id(),
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

        DB::table('notif')->insert(
            [
                'id_request' => $id,
                'id_user' => Auth::user()->id,
                'desc' => 'Pengajuan dilakukan ' . Auth::user()->name,
                'created_at' => now(),

            ]
        );



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

        $currentRole = Auth::user()->role;
        $komentar = $request->input('komentar'); // Ambil komentar dari form

        // Naikkan level kalau masih di bawah 4
        if ($currentRole < 4) {
            DB::table('request')->where('id', $id)->update([
                'level' => $currentRole,
                'status' => 'approve',
                'keterangan' => 'Disetujui oleh ' . Auth::user()->name,
                'updated_at' => now()
            ]);

            DB::table('notif')->insert([
                'id_request' => $id,
                'id_user' => Auth::user()->id,
                'desc' => 'Pengajuan Telah Di Approve oleh ' . Auth::user()->name,
                'created_at' => now(),

            ]);
        } else {
            // Kalau sudah level terakhir (kepsek), tidak perlu naik level
            DB::table('request')->where('id', $id)->update([
                'status' => 'approve',
                'level' => 4,
                'keterangan' => 'Disetujui final oleh ' . Auth::user()->name,
                'updated_at' => now()
            ]);

            DB::table('notif')->insert([
                'id_request' => $id,
                'id_user' => Auth::user()->id,
                'desc' => 'Pengajuan Telah Di setujui final',
                'created_at' => now(),

            ]);
        }

        DB::table('approvals')->insert([
            'request_id' => $id,
            'user_id' => auth()->id(),
            'status' => 'approve',
            'komentar' => $komentar,
            'approved_at' => now(),
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $data = DB::table('request')->where('id', $id)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $currentRole = Auth::user()->role;
        $komentar = $request->input('komentar'); // Ambil komentar dari form

        DB::table('request')->where('id', $id)->update([
            'level' => $currentRole,
            'status' => 'rejected',
            'keterangan' => 'Ditolak oleh ' . Auth::user()->name,
            'updated_at' => now()
        ]);

        DB::table('approvals')->insert([
            'request_id' => $id,
            'user_id' => auth()->id(),
            'status' => 'rejected',
            'komentar' => $komentar,
            'approved_at' => now(),
            'created_at' => now()
        ]);

        DB::table('notif')->insert([
            'id_request' => $id,
            'id_user' => Auth::user()->id,
            'desc' => 'Pengajuan Telah Di Reject oleh ' . Auth::user()->name,
            'created_at' => now(),

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
            'user_id' => Auth::user()->id,
            'parent_id' => null,
            'komentar' => $request->komentar,
            'created_at' => now()
        ]);

        DB::table('notif')->insert([
            'id_request' => $data->request_id,
            'id_user' => Auth::user()->id,
            'desc' => Auth::user()->name . 'Telah Menjawab Komentar',
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Membalas komentar !!');
    }
    public function replyComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:500',
        ]);

        $data = DB::table('approvals')->where('id_approvals', $id)->first();


        DB::table('reply')->insert([
            'approvals_id' => $id,
            'user_id' => $request->user_id,
            'parent_id' => Auth::user()->id,
            'komentar' => $request->komentar,
            'created_at' => now()
        ]);

        DB::table('notif')->insert([
            'id_request' => $data->request_id,
            'id_user' => Auth::user()->id,
            'desc' => Auth::user()->name . 'Telah Menjawab Komentar',
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Membalas komentar !!');
    }
}
