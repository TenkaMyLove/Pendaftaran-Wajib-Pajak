<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Registration::create([
            'npwp' => '02.435.678.9-012.000',
            'nama_lengkap' => 'Budi Santoso',
            'no_ktp' => '3273012345670010',
            'alamat_ktp' => 'Jl. Merdeka No. 45, Bandung',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-05-12',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'budi.santoso@email.com',
            'no_hp' => '081234567890',
            'no_telp_perusahaan' => '0224201234',
            'jenis_npwp' => 'Orang Pribadi',
            'kependudukan' => 'Dalam Negeri',
            'status' => 'Verified',
        ]);

        \App\Models\Registration::create([
            'npwp' => '01.234.567.8-091.000',
            'nama_lengkap' => 'PT Karya Abadi Jaya',
            'no_ktp' => '3273029876540001',
            'alamat_ktp' => 'Kawasan Industri Jababeka Blok C-12, Bekasi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-10-20',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'info@karyaabadi.co.id',
            'no_hp' => '08111222333',
            'no_telp_perusahaan' => '0218983456',
            'jenis_npwp' => 'Badan',
            'kependudukan' => 'Dalam Negeri',
            'status' => 'Pending',
        ]);

        \App\Models\Registration::create([
            'npwp' => '03.987.654.3-021.000',
            'nama_lengkap' => 'Siti Aminah',
            'no_ktp' => '3171025506920005',
            'alamat_ktp' => 'Jl. Sudirman Kav 21, Jakarta Selatan',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1992-06-15',
            'jenis_kelamin' => 'Perempuan',
            'email' => 'siti.aminah@email.com',
            'no_hp' => '085712345678',
            'no_telp_perusahaan' => '0215712345',
            'jenis_npwp' => 'Orang Pribadi',
            'kependudukan' => 'Dalam Negeri',
            'status' => 'Rejected',
        ]);

        \App\Models\Registration::create([
            'npwp' => '09.111.222.3-444.000',
            'nama_lengkap' => 'Chevron Indo-Pacific BUT',
            'no_ktp' => '9900228833441122',
            'alamat_ktp' => 'World Trade Center Lantai 18, Jakarta',
            'tempat_lahir' => 'Houston',
            'tanggal_lahir' => '1970-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'email' => 'tax@chevron-indopacific.com',
            'no_hp' => '081299998888',
            'no_telp_perusahaan' => '0215208888',
            'jenis_npwp' => 'BUT',
            'kependudukan' => 'Luar Negeri',
            'status' => 'Pending',
        ]);
    }
}
