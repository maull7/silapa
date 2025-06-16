<?php

namespace App\Imports;

use App\Models\MasterSoal;
use App\Models\MasterMateri;
use App\Models\MasterJurusan;
use App\Models\MasterKategori;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class SoalImport implements ToModel, WithHeadingRow
{
    private static $rowIndex = 2; // Start from 2 if there's a header
    private $supportedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];

    /**
     * Process a row from the Excel file
     */
    public function model(array $row)
    {
        try {
            // Skip empty rows
            if (empty(array_filter($row))) {
                return null;
            }

            // Get current row number for image processing
            $currentRowNumber = self::$rowIndex++;

            // Get materi ID
            $idMateri = $this->getMateriId($row);

            // Get user-specified categories - IMPORTANT: We use these exactly as specified
            $idKategoriSoal = $this->getKategoriId($row, 'kategori_soal', 'text');
            $idKategoriJawaban = $this->getKategoriId($row, 'kategori_jawaban', 'text');
            $idjurusan = $this->getJurusanId($row);


            // Get category names for logging
            $kategoriSoalName = $this->getKategoriName($idKategoriSoal);
            $kategoriJawabanName = $this->getKategoriName($idKategoriJawaban);


            // Log the categories being used
            Log::info("Row {$currentRowNumber} - Using categories - Question: {$kategoriSoalName}, Answer: {$kategoriJawabanName}");

            // Ensure answer has default value (1 if not provided)
            $jawaban = isset($row['jawaban']) && !empty($row['jawaban']) ? $row['jawaban'] : 1;



            // Create the question record with text data first
            $soal = MasterSoal::create([
                'id_materi' => $idMateri,
                'id_kategori_soal' => $idKategoriSoal,
                'id_kategori_jawaban' => $idKategoriJawaban,
                'id_jurusan' => $idjurusan,
                'soal' => $row['soal'],
                'pilihan_1' => $row['pilihan_1'] ?? null,
                'pilihan_2' => $row['pilihan_2'] ?? null,
                'pilihan_3' => $row['pilihan_3'] ?? null,
                'pilihan_4' => $row['pilihan_4'] ?? null,
                'sts' => isset($row['status']) && ($row['status'] == 'Aktif' || $row['status'] == 1) ? 1 : 0,
                'bobot' => $row['bobot'] ?? 1,
                'jawaban' => $jawaban,
                'type' => $row['type'] ?? 'multiple'
            ]);

            // Process ALL images in this row, regardless of category
            // This is the key change - we always process images and store them properly
            $this->processImagesForRow($soal, $currentRowNumber);

            return $soal;
        } catch (\Exception $e) {
            Log::error('Error importing soal: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Get materi ID from row or use default
     */
    private function getMateriId(array $row): int
    {
        // Use default for materi (take first materi)
        $materi = MasterMateri::first();
        $idMateri = $materi ? $materi->id_materi : 14;

        // If there's a materi column in Excel, try to find matching materi
        if (isset($row['materi']) && !empty($row['materi'])) {
            $materiByName = MasterMateri::where('judul', $row['materi'])->first();
            if ($materiByName) {
                $idMateri = $materiByName->id_materi;
            }
        }

        return $idMateri;
    }
    private function getJurusanId(array $row): int
    {
        // Use default for materi (take first materi)
        $jurusan = MasterJurusan::first();
        $idjurusan = $jurusan->id_jurusan;

        // If there's a jurusan column in Excel, try to find matching jurusan
        if (isset($row['jurusan']) && !empty($row['jurusan'])) {
            $jurusanName = MasterJurusan::where('nama_jurusan', $row['jurusan'])->first();
            if ($jurusanName) {
                $idjurusan = $jurusanName->id_jurusan;
            }
        }



        return $idjurusan;
    }

    /**
     * Get kategori ID from row or use default
     */
    private function getKategoriId(array $row, string $field, string $defaultCategory): int
    {
        // Get default category ID
        $defaultKategori = MasterKategori::where('nama_kategori', $defaultCategory)->first();
        $idKategori = $defaultKategori ? $defaultKategori->id_kategori : 1;

        // If there's a category column in Excel, try to find matching category
        if (isset($row[$field]) && !empty($row[$field])) {
            $kategoriName = trim($row[$field]);
            $kategoriByName = MasterKategori::where('nama_kategori', 'LIKE', "%{$kategoriName}%")->first();

            if ($kategoriByName) {
                $idKategori = $kategoriByName->id_kategori;
                Log::info("Found category '{$kategoriName}' with ID {$idKategori}");
            } else {
                Log::warning("Category '{$kategoriName}' not found, using default");
            }
        }

        return $idKategori;
    }

    /**
     * Get category name by ID
     */
    private function getKategoriName(int $kategoriId): string
    {
        $kategori = MasterKategori::find($kategoriId);
        return $kategori ? $kategori->nama_kategori : 'unknown';
    }

    /**
     * Process ALL images for a row, regardless of category
     * This is the key method that ensures all images are processed correctly
     */
    private function processImagesForRow(MasterSoal $soal, int $rowNumber): void
    {
        try {
            if (!request()->hasFile('file')) {
                Log::warning("No Excel file available for image processing");
                return;
            }

            $spreadsheet = IOFactory::load(request()->file('file'));
            $worksheet = $spreadsheet->getActiveSheet();

            $updates = [];

            // Mapping of Excel columns to database fields
            $columnMappings = [
                'soal' => 'soal',
                'pilihan 1' => 'pilihan_1',
                'pilihan 2' => 'pilihan_2',
                'pilihan 3' => 'pilihan_3',
                'pilihan 4' => 'pilihan_4',
                'pilihan1' => 'pilihan_1',
                'pilihan2' => 'pilihan_2',
                'pilihan3' => 'pilihan_3',
                'pilihan4' => 'pilihan_4',
            ];

            // Process all drawings in the worksheet
            foreach ($worksheet->getDrawingCollection() as $drawing) {
                preg_match('/([A-Z]+)(\d+)/', $drawing->getCoordinates(), $matches);

                if (!isset($matches[1]) || !isset($matches[2])) {
                    continue;
                }

                $column = $matches[1];
                $drawingRowNumber = (int) $matches[2];

                // Only process drawings for the current row
                if ($drawingRowNumber !== $rowNumber) {
                    continue;
                }

                Log::info("Found drawing in row {$rowNumber}, column {$column}");

                // Get image content and extension
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
                    $imageContents = '';
                    while (!feof($zipReader)) {
                        $imageContents .= fread($zipReader, 1024);
                    }
                    fclose($zipReader);
                    $extension = $drawing->getExtension();
                }

                // Skip unsupported image types
                if (!in_array(strtolower($extension), $this->supportedImageTypes)) {
                    Log::warning("Skipping unsupported image type: {$extension}");
                    continue;
                }

                // Determine which field this image belongs to
                $fieldName = null;
                $columnIndex = Coordinate::columnIndexFromString($column);
                $headerValue = $worksheet->getCellByColumnAndRow($columnIndex, 1)->getValue();
                $headerValue = strtolower(trim($headerValue));

                foreach ($columnMappings as $excelColumn => $dbField) {
                    if (str_replace(['_', ' '], '', $headerValue) === str_replace(['_', ' '], '', $excelColumn)) {
                        $fieldName = $dbField;
                        break;
                    }
                }

                if (!$fieldName) {
                    Log::warning("Could not determine field for column {$column}");
                    continue;
                }

                Log::info("Processing image for field {$fieldName}");

                // Save the image - ALWAYS save images regardless of category
                $baseDirectory = 'uploads/';
                $subDirectory = $fieldName === 'soal' ? 'soal/' : 'jawaban/';
                $directory = public_path($baseDirectory . $subDirectory);

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $fileName = time() . '_' . $fieldName . '_' . uniqid() . '.' . $extension;
                $filePath = $directory . $fileName;

                file_put_contents($filePath, $imageContents);

                $relativePath = $baseDirectory . $subDirectory . $fileName;
                $updates[$fieldName] = $relativePath;

                Log::info("Saved image to {$relativePath}");
            }

            // Update the record with image paths if any were found
            if (!empty($updates)) {
                $soal->update($updates);
                Log::info("Updated record {$soal->id} with image paths: " . json_encode($updates));
            }
        } catch (\Exception $e) {
            Log::error('Error processing images: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
