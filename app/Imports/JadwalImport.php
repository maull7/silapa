<?php
namespace App\Imports;

use App\Models\MasterJadwal;
use App\Models\MasterGuru;
use App\Models\MasterSiswa;
use App\Models\MasterKelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Get the ID of the guru based on nama_guru (if provided)
        $idGuru = null;
        if (!empty($row['nama_guru']) && $row['nama_guru'] != '-') {
            $guru = MasterGuru::where('nama_guru', $row['nama_guru'])->first();
            if ($guru) {
                $idGuru = $guru->id_guru;
            }
        }

        // Get the ID of the siswa based on nama_siswa (if provided)
        $idSiswa = null;
        if (!empty($row['nama_siswa']) && $row['nama_siswa'] != '-') {
            $siswa = MasterSiswa::where('nama_siswa', $row['nama_siswa'])->first();
            if ($siswa) {
                $idSiswa = $siswa->id_siswa;
            }
        }

        // Cari ID kelas atau gunakan "Kelas Dihapus"
        $idKelas = null;
        $useDeletedClass = false;
        
        // Jika kolom nama_kelas kosong atau berisi "Kelas Dihapus", gunakan kelas "Kelas Dihapus"
        if (empty($row['nama_kelas']) || $row['nama_kelas'] == 'Kelas Dihapus') {
            $useDeletedClass = true;
        } else {
            // Coba cari kelas berdasarkan nama
            $kelas = MasterKelas::where('nama_kelas', $row['nama_kelas'])->first();
            if ($kelas) {
                $idKelas = $kelas->id_kelas;
            } else {
                // Jika kelas tidak ditemukan, gunakan "Kelas Dihapus"
                $useDeletedClass = true;
            }
        }
        
        // Jika perlu menggunakan "Kelas Dihapus"
        if ($useDeletedClass) {
            // Cari kelas yang bernama "Kelas Dihapus"
            $deletedClass = MasterKelas::where('nama_kelas', 'Kelas Dihapus')->first();
            
            if ($deletedClass) {
                // Gunakan ID dari kelas "Kelas Dihapus" yang sudah ada
                $idKelas = $deletedClass->id_kelas;
            } else {
                // Cari jurusan pertama atau yang valid untuk digunakan
                $firstJurusan = \App\Models\MasterJurusan::first();
                $idJurusan = $firstJurusan ? $firstJurusan->id_jurusan : 1; // Gunakan ID 1 jika tidak ada
                
                // Jika "Kelas Dihapus" belum ada di database, buat baru
                $newDeletedClass = new MasterKelas();
                $newDeletedClass->nama_kelas = 'Kelas Dihapus';
                $newDeletedClass->id_jurusan = $idJurusan; // Tambahkan id_jurusan
                $newDeletedClass->save();
                
                $idKelas = $newDeletedClass->id_kelas;
            }
        }

        // Convert status from text to number
        $status = (!empty($row['status']) && strtolower($row['status']) == 'aktif') ? 1 : 0;

        // Convert type from text to number
        $type = (!empty($row['tipe']) && strtolower($row['tipe']) == 'siswa') ? 2 : 1;

        return new MasterJadwal([
            'id_jadwal' => $row['id_jadwal'] ?? null,
            'hari' => $row['hari'] ?? '',
            'id_guru' => $idGuru,
            'id_siswa' => $idSiswa,
            'id_kelas' => $idKelas, // Sekarang selalu berisi ID yang valid
            'jam_in' => $row['jam_masuk'] ?? '00:00',
            'jam_out' => $row['jam_keluar'] ?? '00:00',
            'nama_jadwal' => $row['nama_jadwal'] ?? '',
            'sts' => $status,
            'type' => $type,
        ]);
    }
}