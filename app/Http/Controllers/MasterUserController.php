<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterGuru;

use App\Models\MasterKelas;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use App\Models\MasterJurusan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MasterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $users = DB::table('users')->where('role', 0)
            ->leftJoin('master_jabatan', 'master_jabatan.id', '=', 'users.id_jabatan')
            ->select('users.*', 'master_jabatan.nama_jabatan')
            ->get();

        return view('master_users.index', compact('users'));
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
            'password' => 'required|string|min:6',
            'nip' => 'required|string',
        ]);

        $jabatan =  DB::table('master_jabatan')->where('nama_jabatan', 'admin')->first();
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']); // Hash password
        $user->nip = $validated['nip'];
        $user->id_jabatan = $jabatan->id;
        $user->role = 0;
        $user->save();

        return redirect()->back()->with('success', 'Menambahkan Admin!');
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
        $user = User::findOrFail($id);
        return view('master_users.edit', compact('user'));
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
            'password' => 'nullable|string|min:6',
            'nip' => 'required|string',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->nip = $validated['nip'];

        // Hanya update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/master_user')->with('success', 'Data user berhasil diperbarui!');
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

        return redirect()->route('master_user.index')->with('success', 'Berhasil menghapus User.');
    }

    public function updateStatus(Request $request) {}

    public function updateStatusBulk(Request $request) {}


    /**
     * Reset user password to default (12345678)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Set default password
            $defaultPassword = '12345678';
            $user->password = Hash::make($defaultPassword);
            $user->first_login = 1; // Require password change on next login
            $user->save();

            DB::commit();

            return redirect()->route('master_user.index')
                ->with('success', "Password untuk {$user->name} berhasil direset ke default (12345678)");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master_user.index')
                ->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
}
