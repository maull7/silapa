<?php

namespace App\Imports;

use App\Models\User;
use App\Models\MasterGuru;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Illuminate\Support\Facades\Hash;

class GuruImport implements ToModel, WithHeadingRow
{

    private static $rowIndex = 2; // Mulai dari 2 jika ada header

    public function model(array $row)
    {
        // Simpan data guru ke database tanpa foto terlebih dahulu
        $guru = MasterGuru::create([
            'nama_guru' => $row['nama_guru'],
            'alamat_guru' => $row['alamat'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'email' => $row['email'],
            'nip' => $row['nip'],
            'nik' => $row['nik'],
            'foto' => null // Foto sementara null
        ]);

        // Create user account for the student
        User::create([
            'name' => $row['nama_guru'],
            'email' => $row['email'],
            'role' => 1, // Assuming 0 is the role for students
            'password' => Hash::make('12345678'), // Default password
            'id_jurusan' => $row['jurusan'] ?? 0,
            'nip' => $row['nip'],
            'status' => 1, // Active
            'first_login' => 1 // Needs to change password on first login
        ]);



        // Ambil file Excel yang sedang diimport
        $spreadsheet = IOFactory::load(request()->file('file'));

        // Ambil nomor baris dari data yang sedang diproses
        $rowNumber = $this->getCurrentRowIndex();

        foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
            // Ambil koordinat gambar (misal: B2 → dapat angka 2 sebagai baris)
            preg_match('/(\d+)/', $drawing->getCoordinates(), $matches);
            $drawingRowNumber = isset($matches[0]) ? (int) $matches[0] : null;

            // Jika gambar sesuai dengan baris data, simpan
            if ($drawingRowNumber === $rowNumber) {
                $imageContents = null;
                $extension = null;

                if ($drawing instanceof MemoryDrawing) {
                    // Handle gambar dari MemoryDrawing
                    ob_start();
                    call_user_func(
                        $drawing->getRenderingFunction(),
                        $drawing->getImageResource()
                    );
                    $imageContents = ob_get_contents();
                    ob_end_clean();

                    switch ($drawing->getMimeType()) {
                        case MemoryDrawing::MIMETYPE_PNG:
                            $extension = 'png';
                            break;
                        case MemoryDrawing::MIMETYPE_GIF:
                            $extension = 'gif';
                            break;
                        case MemoryDrawing::MIMETYPE_JPEG:
                            $extension = 'jpg';
                            break;
                    }
                } else {
                    // Handle gambar dari file sistem
                    $zipReader = fopen($drawing->getPath(), 'r');
                    $imageContents = stream_get_contents($zipReader);
                    fclose($zipReader);
                    $extension = $drawing->getExtension();
                }

                // Simpan gambar ke folder public/uploads/guru/
                if ($imageContents && $extension) {
                    $directory = public_path('uploads/guru/');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    $fileName = time() . '.' . $extension;
                    $filePath = $directory . $fileName;

                    // Simpan gambar ke direktori public/uploads/guru/
                    file_put_contents($filePath, $imageContents);

                    // Update kolom foto di database
                    $guru->update([
                        'foto' => $fileName
                    ]);

                    break; // Hentikan loop setelah menemukan gambar yang cocok
                }
            }
        }

        return $guru;
    }


    private function getCurrentRowIndex()
    {
        return self::$rowIndex++;
    }
}
