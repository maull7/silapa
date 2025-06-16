<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('login');
        }
    }


    public function actionLogin(Request $request)
    {

        $datalogin = [
            'nip' => $request->nip,
            'password' => $request->password,
        ];
        // dd($request->all());

        if (Auth::attempt($datalogin)) {
            return redirect('/home');
        } else {
            Session::flash('error', 'Nip / Password Salah !');
            return redirect('/');
            //return redirect('/');
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function actionLogout()
    {
        Auth::logout();
        Session::flush(); // Add session flush for complete logout
        return redirect('/');
    }

    public function changeView()
    {
        if (Auth::check()) {
            return view('change');
        } else {
            return view('login');
        }
    }
    public function changePassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui password. Periksa kembali input Anda.');
        }

        // Jika validasi sukses
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->first_login = 0; // Set jadi 0 menandakan bukan login pertama
        $user->save();

        session()->flash('success', 'Password berhasil diperbarui.');

        // Redirect sesuai role
        if ($user->role == 0) {
            return redirect('/siswaIn');
        } else {
            return redirect('/home');
        }
    }
}
