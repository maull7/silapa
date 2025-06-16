<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
            $query->where('request.status', $request->status);
        }

        $requests = $query->orderBy('request.tanggal', 'desc')->get();

        return view('laporan.index', compact('requests'));
    }
}
