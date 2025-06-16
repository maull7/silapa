<?php

namespace App\Imports;

use App\Models\MasterSiswa;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


class SiswaImport implements ToModel, WithHeadingRow
{
    private static $rowIndex = 2; // Mulai dari 2 jika ada header

    public function model(array $row)
    {
        // Create the student record
        $siswa = MasterSiswa::create([
            'nama_siswa' => $row['nama_siswa'],
            'alamat_siswa' => $row['alamat'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'nip' => $row['nip'],
            'nik' => $row['nik'],
            'foto' => null,
            'id_jurusan' => $row['jurusan'],
            'email' => $row['email'],
            'id_kelas' => $row['kelas']
        ]);

        // Create user account for the student
        User::create([
            'name' => $row['nama_siswa'],
            'email' => $row['email'],
            'role' => 0, // Assuming 0 is the role for students
            'password' => Hash::make('12345678'), // Default password
            'id_jurusan' => $row['jurusan'],
            'status' => 1, // Active
            'first_login' => 1,
            'nip' => $row['nip'] // Needs to change password on first login
        ]);

        // Handle image import
        $this->processStudentImage($siswa);

        return $siswa;
    }

    private function processStudentImage($siswa)
    {
        try {
            $spreadsheet = IOFactory::load(request()->file('file'));
            $rowNumber = $this->getCurrentRowIndex();

            foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
                preg_match('/(\d+)/', $drawing->getCoordinates(), $matches);
                $drawingRowNumber = isset($matches[0]) ? (int) $matches[0] : null;

                if ($drawingRowNumber === $rowNumber) {
                    $imageContents = null;
                    $extension = null;

                    if ($drawing instanceof MemoryDrawing) {
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
                        $zipReader = fopen($drawing->getPath(), 'r');
                        $imageContents = stream_get_contents($zipReader);
                        fclose($zipReader);
                        $extension = $drawing->getExtension();
                    }

                    if ($imageContents && $extension) {
                        $directory = public_path('uploads/siswa/');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                        }

                        $fileName = time() . '.' . $extension;
                        $filePath = $directory . $fileName;

                        file_put_contents($filePath, $imageContents);

                        $siswa->update([
                            'foto' => $fileName
                        ]);

                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle error if image processing fails
            \Log::error('Error processing student image: ' . $e->getMessage());
        }
    }

    private function getCurrentRowIndex()
    {
        return self::$rowIndex++;
    }
}
