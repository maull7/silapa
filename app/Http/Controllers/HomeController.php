<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Soal;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Jurusan;
use App\Models\MasterGuru;
use App\Models\MasterQuiz;
use App\Models\MasterSoal;
use App\Models\MasterKelas;
use App\Models\MasterSiswa;
use App\Models\MasterJadwal;
use App\Models\MasterMateri;
use Illuminate\Http\Request;
use App\Models\MasterJurusan;
use App\Models\MasterKategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $total = DB::table('request')->where('user_id', $userId)->count();
        $approve = DB::table('request')->where('level', 4)->where('status', 'approve')->where('user_id', $userId)->count();
        $reject = DB::table('request')->where('status', 'rejected')->where('user_id', $userId)->count();

        $totalAll = DB::table('request')->count();
        $approveAll = DB::table('request')->where('level', 4)->where('status', 'approve')->count();
        $rejectAll = DB::table('request')->where('status', 'rejected')->count();


        return view('home', compact('total', 'approve', 'reject', 'totalAll', 'approveAll', 'rejectAll'));
    }
}
