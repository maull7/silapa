<?php

use Illuminate\Support\Facades\DB;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/app.php';

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update["message"]["text"]) && str_starts_with($update["message"]["text"], "/start")) {
        $message = $update["message"]["text"];
        $chatId = $update["message"]["chat"]["id"];
        $userId = explode(" ", $message)[1] ?? null;

        if ($userId) {
                // Cek apakah sudah ada
                $existing = DB::table('tele_bot')->where('user_id', $userId)->first();

                if ($existing) {
                        // Update jika sudah ada
                        DB::table('tele_bot')->where('user_id', $userId)->update([
                                'chat_id' => $chatId,
                                'updated_at' => now()
                        ]);
                } else {
                        // Insert jika belum ada
                        DB::table('tele_bot')->insert([
                                'user_id' => $userId,
                                'chat_id' => $chatId,
                                'created_at' => now(),
                                'updated_at' => now()
                        ]);
                }
        }

        file_get_contents("https://api.telegram.org/bot7640539385:AAHE-qfya2zuBgfgSBJ4MXf15Nf3EgRiyDY/sendMessage?" . http_build_query([
                'chat_id' => $chatId,
                'text' => 'Akun kamu berhasil dihubungkan dengan sistem.'
        ]));
}
