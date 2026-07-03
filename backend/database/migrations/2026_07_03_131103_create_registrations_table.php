<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('npwp', 20);
            $table->string('nama_lengkap', 150);
            $table->string('no_ktp', 20);
            $table->text('alamat_ktp');
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('email', 100);
            $table->string('no_hp', 20);
            $table->string('no_telp_perusahaan', 20);
            $table->enum('jenis_npwp', ['Orang Pribadi', 'Badan', 'BUT']);
            $table->enum('kependudukan', ['Dalam Negeri', 'Luar Negeri']);
            $table->enum('status', ['Pending', 'Verified', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
