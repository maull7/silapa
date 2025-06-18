<?php

use App\Exports\RequestExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotifController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterPenggunaController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/telegram-webhook', function () {
    $update = json_decode(file_get_contents('php://input'), true);

    if (isset($update["message"]["text"]) && str_starts_with($update["message"]["text"], "/start")) {
        $message = $update["message"]["text"];
        $chatId = $update["message"]["chat"]["id"];
        $parts = explode(" ", $message);
        $userId = $parts[1] ?? null;

        if ($userId) {
            try {
                $now = Carbon::now();

                DB::table('tele_bot')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'chat_id' => $chatId,
                        'updated_at' => $now,
                        'created_at' => $now, // akan diabaikan jika sudah ada
                    ]
                );

                // Kirim pesan sukses
                Http::get("https://api.telegram.org/bot7640539385:AAHE-qfya2zuBgfgSBJ4MXf15Nf3EgRiyDY/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => '✅ Akun kamu berhasil dihubungkan dengan sistem.'
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan data Telegram: ' . $e->getMessage());

                Http::get("https://api.telegram.org/bot7640539385:AAHE-qfya2zuBgfgSBJ4MXf15Nf3EgRiyDY/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => '❌ Gagal menghubungkan akun kamu. Silakan coba lagi nanti.'
                ]);
            }
        } else {
            // Jika user_id tidak dikirim atau kosong
            Http::get("https://api.telegram.org/bot7640539385:AAHE-qfya2zuBgfgSBJ4MXf15Nf3EgRiyDY/sendMessage", [
                'chat_id' => $chatId,
                'text' => '⚠️ Perintah /start tidak valid. Format yang benar: /start <user_id>'
            ]);
        }
    }

    return response('OK');
});





// Route untuk login
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/actionLogin', [LoginController::class, 'actionLogin'])->name('actionLogin');
//route untuk semua role
Route::group(['middleware' => 'auth'], function () {
    //user atau pengguna
    Route::resource('master_user', MasterUserController::class);
    Route::post('/master_user/{id}/reset-password', [MasterUserController::class, 'resetPassword'])
        ->name('master_user.reset_password')
        ->middleware('auth');
    // Add this route
    Route::post('/master-user/{id}/reset-password', [MasterUserController::class, 'resetPassword'])
        ->name('master_user.reset_password');


    Route::resource('master_jabatan', MasterJabatanController::class);


    //pengguna
    Route::resource('master_pengguna', MasterPenggunaController::class);
    // Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/changePassword', [LoginController::class, 'changeView'])->name('change.view');
    Route::post('/change-password', [LoginController::class, 'changePassword'])->name('change.password');
    Route::get('/logout', [LoginController::class, 'actionLogout'])->name('actionLogout');
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/ajukan-pengajuan', [PengajuanController::class, 'create'])->name('pengajuan.create');

    //route ajuan
    Route::post('/tambah-pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::delete('/hapus-pengajuan/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    Route::get('/pengajuan/edit/{id}', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('/update-pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::get('/verifikasi-pengajuan', [PengajuanController::class, 'verify'])->name('pengajuan.verify');

    //route acc reject

    Route::post('/approve/{id}', [PengajuanController::class, 'approve'])->name('approve');
    Route::post('/reject/{id}', [PengajuanController::class, 'reject'])->name('reject');
    Route::post('/approvals-reply/{id}', [PengajuanController::class, 'reply'])->name('approval.reply');
    Route::post('/comment-reply/{id}', [PengajuanController::class, 'replyComment'])->name('comment.reply');
    Route::get('/pengajuan-approve', [PengajuanController::class, 'success'])->name('success');
    Route::get('/pengajuan-reject', [PengajuanController::class, 'cancel'])->name('cancel');

    Route::get('/detail-pengajuan/{id}', [PengajuanController::class, 'detail'])->name('detail');

    //notif
    Route::get('/read', [NotifController::class, 'read'])->name('notif.read');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');


    Route::get('/laporan-approval-export', function () {
        $requests = DB::table('request')
            ->join('users', 'users.id', '=', 'request.user_id')
            ->select('request.*', 'users.name')
            ->when(request('start_date') && request('end_date'), function ($query) {
                $query->whereBetween('request.tanggal', [request('start_date'), request('end_date')]);
            })
            ->when(request('status'), function ($query) {
                $query->where('request.status', request('status'));
            })
            ->orderBy('request.tanggal', 'desc')
            ->get();

        return Excel::download(new RequestExport($requests), 'laporan_approval.xlsx');
    })->name('laporan_approval.export');
});
// //master untuk role admin mengandung master master
// Route::group(['middleware' => ['auth', 'admin.only']], function () {


    
// });
