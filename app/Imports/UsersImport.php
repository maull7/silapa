<?php

namespace App\Imports;

use App\Models\User;
use App\Models\MasterJurusan;
use App\Models\MasterKelas;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find jurusan_id based on nama_jurusan
        $jurusan_id = null;
        if (!empty($row['jurusan'])) {
            $jurusan = MasterJurusan::where('nama_jurusan', $row['jurusan'])->first();
            if ($jurusan) {
                $jurusan_id = $jurusan->id_jurusan;
            }
        }

        // Find kelas_id based on nama_kelas
        $kelas_id = null;
        if (!empty($row['kelas'])) {
            $kelas = MasterKelas::where('nama_kelas', $row['kelas'])->first();
            if ($kelas) {
                $kelas_id = $kelas->id_kelas;
            }
        }

        // Handle NIP field with case-insensitive check
        $nip = null;
        if (isset($row['nip'])) {
            $nip = $row['nip'];
        } elseif (isset($row['NIP'])) {
            $nip = $row['NIP'];
        }

        if ($row['role'] == 'admin') {
            $role = 2;
        } else if ($row['role'] == 'guru') {
            $role = 1;
        } else {
            $role = 0;
        }

        return new User([
            'name' => $row['nama'],
            'email' => $row['email'],
            'role' => $role,
            'nip' => $nip,
            'jurusan_id' => $jurusan_id,
            'kelas_id' => $kelas_id,
            'password' => Hash::make($row['password']),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,guru,siswa',
            'nip' => 'nullable|required_if:role,guru',
            'password' => 'required|min:6',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role harus salah satu dari: admin, guru, siswa.',
            'nip.required_if' => 'NIP wajib diisi untuk role guru.',
            'password.min' => 'Password minimal 6 karakter.',
        ];
    }
}
