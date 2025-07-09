<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BerkasBensatController extends Controller
{
    public function create($id)
    {
        $request = DB::table('request')->where('id', $id)->first();

        // Cek apakah sudah pernah upload file bensat
        $bensat = DB::table('berkas_bensat')->where('id_request', $id)->first();

        return view('bensat.create', compact('request', 'bensat'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'upload_1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'upload_2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $existing = DB::table('berkas_bensat')->where('id_request', $id)->first();

        $data = [
            'user_id' => auth()->id(),
            'id_request' => $id,
            'updated_at' => now(),
        ];

        $uploadPath = public_path('berkas_bensat');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Upload File 1
        if ($request->hasFile('upload_1')) {
            $file1 = $request->file('upload_1');
            $fileName1 = now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $file1->getClientOriginalExtension();
            $file1->move($uploadPath, $fileName1);
            $data['upload_1'] = $fileName1;
        }

        // Upload File 2
        if ($request->hasFile('upload_2')) {
            $file2 = $request->file('upload_2');
            $fileName2 = now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $file2->getClientOriginalExtension();
            $file2->move($uploadPath, $fileName2);
            $data['upload_2'] = $fileName2;
        }

        if ($existing) {
            DB::table('berkas_bensat')->where('id_request', $id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('berkas_bensat')->insert($data);
        }

        return redirect()->route('success')->with('success', 'Berkas bensat berhasil disimpan.');
    }
}
