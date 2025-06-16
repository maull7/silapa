<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeView extends Command
{
    protected $signature = 'make:view {name}';
    protected $description = 'Membuat file view baru di resources/views/';

    public function handle()
    {
        $name = $this->argument('name');
        $path = resource_path("views/" . str_replace('.', '/', $name) . ".blade.php");

        // Cek apakah file sudah ada
        if (File::exists($path)) {
            $this->error("View {$name} sudah ada!");
            return;
        }

        // Buat folder jika belum ada
        File::ensureDirectoryExists(dirname($path));

        // Buat file Blade kosong
        File::put($path, "<!-- View: {$name} -->");

        $this->info("View {$name} berhasil dibuat: {$path}");
    }
}
