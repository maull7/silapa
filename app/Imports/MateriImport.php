<?php

namespace App\Imports;

use App\Models\MasterMateri;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class MateriImport implements ToModel, WithHeadingRow
{
    private static $rowIndex = 2; // Mulai dari 2 jika ada header

    public function model(array $row)
    {

        // Buat data master materi dengan default null untuk foto dan file_materi
        $materi = MasterMateri::create([
            'judul'         => $row['judul'],
            'deskripsi'     => $row['deskripsi'],
            'id_kelas'      => $row['id_kelas'],
            'id_kategori'   => $row['id_kategori'],
            'img'           => null,
            'file_materi'   => null,
            'sts'           => $row['status'] == 'Aktif' ? 1 : 0,
            'durasi' => $row['durasi'] ?? 0
        ]);



        // Ambil file Excel yang sedang diimport
        $spreadsheet = IOFactory::load(request()->file('file'));
        // Nomor baris data yang sedang diproses
        $rowNumber = $this->getCurrentRowIndex();

        // Loop untuk proses drawing dari sheet
        foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
            // Dapatkan koordinat misal "B2" atau "C2"
            preg_match('/([A-Z]+)(\d+)/', $drawing->getCoordinates(), $matches);
            if (count($matches) < 3) {
                continue;
            }
            $colLetter = $matches[1]; // misal: "B" atau "C"
            $drawingRowNumber = (int)$matches[2];

            // Pastikan drawing berada pada baris data yang sama
            if ($drawingRowNumber === $rowNumber) {
                // --- Proses untuk foto (misalnya gambar berada di kolom B) ---
                if ($colLetter == 'F') {
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
                        $directory = public_path('uploads/logo/');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                        }

                        $fileName = time() . '.' . $extension;
                        $filePath = $directory . $fileName;
                        file_put_contents($filePath, $imageContents);

                        // Update kolom foto pada database
                        $materi->update(['img' => $fileName]);
                    }
                }

                // --- Proses untuk file materi (misalnya file berada di kolom C) ---
                if ($colLetter == 'G') {
                    $fileContents = null;
                    $extension = null;

                    // Jika drawing merupakan image (MemoryDrawing) atau file
                    if ($drawing instanceof MemoryDrawing) {
                        ob_start();
                        call_user_func(
                            $drawing->getRenderingFunction(),
                            $drawing->getImageResource()
                        );
                        $fileContents = ob_get_contents();
                        ob_end_clean();

                        // Tentukan ekstensi berdasarkan mime type
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
                            default:
                                $extension = 'bin';
                                break;
                        }
                    } else {
                        $zipReader = fopen($drawing->getPath(), 'r');
                        $fileContents = stream_get_contents($zipReader);
                        fclose($zipReader);
                        $extension = $drawing->getExtension();
                    }

                    if ($fileContents && $extension) {
                        $directory = public_path('uploads/materi/');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                        }

                        $fileName = time() . '_materi.' . $extension;
                        $filePath = $directory . $fileName;
                        file_put_contents($filePath, $fileContents);

                        // Update kolom file_materi pada database
                        $materi->update(['file_materi' => $fileName]);
                    }
                }
            }
        }

        /* 
       Jika file_materi tidak didapat dari drawing, periksa apakah ada nilai pada cell 
       yang berisi data file materi (bisa berupa URL, teks, atau data base64 untuk file seperti PDF/PPT)
    */
        if (empty($materi->file_materi) && !empty($row['file_materi'])) {
            // Jika berupa URL
            if (filter_var($row['file_materi'], FILTER_VALIDATE_URL)) {
                $materi->update(['file_materi' => $row['file_materi']]);
            } else {
                // Cek apakah data merupakan base64 (biasanya untuk image atau dokumen yang di-encode)
                if (preg_match('/^data:(.*?);base64,/', $row['file_materi'])) {
                    // Misal: data:application/pdf;base64,....
                    preg_match('/^data:(.*?);base64,/', $row['file_materi'], $mimeMatch);
                    $mimeType = $mimeMatch[1] ?? 'application/octet-stream';
                    $extension = 'txt';
                    if (strpos($mimeType, 'pdf') !== false) {
                        $extension = 'pdf';
                    } elseif (strpos($mimeType, 'ms-powerpoint') !== false || strpos($mimeType, 'vnd.ms-powerpoint') !== false) {
                        $extension = 'ppt';
                    } elseif (strpos($mimeType, 'image/') === 0) {
                        $extension = str_replace('image/', '', $mimeType);
                    }

                    // Pisahkan bagian base64-nya
                    $dataParts = explode(',', $row['file_materi']);
                    if (count($dataParts) === 2) {
                        $base64String = $dataParts[1];
                        $fileContents = base64_decode($base64String);

                        $directory = public_path('uploads/materi/');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                        }
                        $fileName = time() . '_materi.' . $extension;
                        $filePath = $directory . $fileName;
                        file_put_contents($filePath, $fileContents);

                        $materi->update(['file_materi' => $fileName]);
                    }
                } else {
                    // Jika bukan URL atau base64, simpan langsung sebagai teks (misalnya path atau keterangan)
                    $materi->update(['file_materi' => $row['file_materi']]);
                }
            }
        }

        return $materi;
    }

    private function getCurrentRowIndex()
    {
        return self::$rowIndex++;
    }
}
