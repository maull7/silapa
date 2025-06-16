<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MasterPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')
            ->join('master_jabatan', 'master_jabatan.id', '=', 'users.id_jabatan')
            ->whereNot('role', 0)
            ->select('users.*', 'master_jabatan.nama_jabatan')
            ->get();
        $jabatan = DB::table('master_jabatan')->whereNot('nama_jabatan', 'admin')->get();
        $walas = DB::table('users')->where('role', 2)->get();
        return view('master_pengguna.index', compact('users', 'jabatan', 'walas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nip' => 'required|string',
            'id_jabatan' => 'required|exists:master_jabatan,id',
            'role' => 'required'
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->nip = $validated['nip'];
        $user->id_jabatan = $validated['id_jabatan'];
        $user->role = $request->role;
        $user->save();
        if ($request->role == 1) {
            DB::table('map')->insert([
                'id_parent' => $request->id_parent,
                'id_child' => $user->id
            ]);
        }

        return redirect()->back()->with('success', 'Menambahkan Pengguna!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jabatan = DB::table('master_jabatan')->whereNot('nama_jabatan', 'admin')->get();
        $walas = DB::table('users')->where('role', 2)->get();
        $map = DB::table('map')->get();

        $user = User::findOrFail($id);

        // cari id_parent dari map jika user siswa
        $parentId = null;
        if ($user->role == 1) {
            $mapped = $map->where('id_child', $user->id)->first();
            $parentId = $mapped->id_parent ?? null;
        }

        return view('master_pengguna.edit', compact('user', 'jabatan', 'walas', 'map', 'parentId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'nip' => 'required|string',
            'id_jabatan' => 'required|exists:master_jabatan,id',
            'role' => 'required'
        ]);
        $mapId = DB::table('map')->where('id_child', $id)->first();
        if ($request->role == 1) {
            DB::table('map')->where('id_map', $mapId->id_map)->update([
                'id_parent' => $request->id_parent,
                'id_child' => $user->id
            ]);
        }



        // Update data user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->nip = $validated['nip'];
        $user->id_jabatan = $validated['id_jabatan'];
        $user->role = $validated['role'];

        // Hanya update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/master_pengguna')->with('success', 'Data user berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Menghapus Data Pengguna !');
    }
}
