<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'npwp',
        'nama_lengkap',
        'no_ktp',
        'alamat_ktp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'email',
        'no_hp',
        'no_telp_perusahaan',
        'jenis_npwp',
        'kependudukan',
        'status',
    ];
}
