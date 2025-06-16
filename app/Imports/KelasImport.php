<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\MasterKelas;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class KelasImport implements ToModel, WithHeadingRow
{
    private static $rowIndex = 2; // Mulai dari 2 jika ada header
    public function model(array $row)
    {
        $kelas =  MasterKelas::create([
            'nama_kelas' => $row['nama_kelas'],
            'id_jurusan' => $row['id_jurusan'],
            'sts' => $row['status'] == 'Aktif' ? 1 : 0,
            'foto' => null,
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


                if ($imageContents && $extension) {
                    $directory = public_path('uploads/kelas/');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    $fileName = time() . '.' . $extension;
                    $filePath = $directory . $fileName;


                    file_put_contents($filePath, $imageContents);

                    // Update kolom foto di database
                    $kelas->update([
                        'foto' => $fileName
                    ]);

                    break; // Hentikan loop setelah menemukan gambar yang cocok
                }
            }
        }

        return $kelas;
    }

    private function getCurrentRowIndex()
    {
        return self::$rowIndex++;
    }
}
