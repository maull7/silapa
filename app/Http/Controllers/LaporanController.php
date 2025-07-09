<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('request')
            ->join('users', 'users.id', '=', 'request.user_id')
            ->select('request.*', 'users.name');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('request.tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            if ($request->status === 'approve') {
                // Kalau cari yang approve, pastikan level-nya 4
                $query->where('request.status', 'approve')
                    ->where('request.level', 4);
            } else {
                $query->where('request.status', $request->status);
            }
        }

        $requests = $query->orderBy('request.tanggal', 'desc')->get();

        return view('laporan.index', compact('requests'));
    }
    public function detail($id)
    {
        // Ambil data request + user
        $data = DB::table('request')
            ->join('users', 'users.id', '=', 'request.user_id')
            ->where('request.id', $id)
            ->select('request.*', 'users.name')
            ->first();

        // Ambil upload dari tabel request_uploads
        $fileUploads = DB::table('request_uploads')
            ->where('id_request', $id)
            ->get();

        // Ambil file bensat jika ada
        $bensat = DB::table('berkas_bensat')
            ->join('users', 'users.id', '=', 'berkas_bensat.user_id')
            ->where('id_request', $id)
            ->first();

        return view('laporan.detail', compact('data', 'fileUploads', 'bensat'));
    }


    public function downloadZip($id)
    {
        $request = DB::table('request')->where('id', $id)->first();
        $uploads = DB::table('request_uploads')->where('id_request', $id)->get();
        $bensat = DB::table('berkas_bensat')->where('id_request', $id)->first();

        $zipFileName = 'pengajuan_' . $id . '.zip';
        $zipPath = public_path($zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Uploads dari request_uploads
            foreach ($uploads as $file) {
                $filePath = public_path('request_uploads/' . $file->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, 'request_uploads/' . $file->file_path);
                }
            }

            // Upload dari bensat
            if ($bensat) {
                foreach (['upload_1', 'upload_2'] as $field) {
                    if (!empty($bensat->$field)) {
                        $filePath = public_path('berkas_bensat/' . $bensat->$field);
                        if (file_exists($filePath)) {
                            $zip->addFile($filePath, 'berkas_bensat/' . $bensat->$field);
                        }
                    }
                }
            }

            $zip->close();
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }
    }
}
