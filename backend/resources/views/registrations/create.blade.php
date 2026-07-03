@extends('layouts.app')

@section('title', 'Form Pendaftaran Wajib Pajak')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
        <!-- Title Header -->
        <div class="px-6 py-8 border-b border-slate-800 bg-gradient-to-br from-slate-900 via-slate-900 to-amber-950/20">
            <h1 class="text-xl font-bold text-slate-100 tracking-tight">FORM PENDAFTARAN</h1>
            <p class="text-xs text-slate-400 mt-1">Harap isi data dengan lengkap untuk pendaftaran wajib pajak.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('registrations.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            @if($errors->any())
                <div class="p-4 rounded-xl border border-rose-500/20 bg-rose-500/10 text-rose-300 text-xs space-y-1">
                    <p class="font-semibold text-sm">Terjadi kesalahan input:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- NPWP -->
            <div class="space-y-1.5">
                <label for="npwp" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nomor Pokok Wajib Pajak (NPWP)</label>
                <input type="text" id="npwp" name="npwp" value="{{ old('npwp') }}" placeholder="00.000.000.0-000.000" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>

            <!-- Nama Lengkap Asli -->
            <div class="space-y-1.5">
                <label for="nama_lengkap" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Lengkap Asli</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Nama Lengkap sesuai identitas" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>

            <!-- No. KTP -->
            <div class="space-y-1.5">
                <label for="no_ktp" class="text-xs font-semibold uppercase tracking-wider text-slate-400">No. KTP</label>
                <input type="text" id="no_ktp" name="no_ktp" value="{{ old('no_ktp') }}" placeholder="Nomor Induk Kependudukan (NIK)" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>

            <!-- Alamat KTP -->
            <div class="space-y-1.5">
                <label for="alamat_ktp" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat KTP</label>
                <textarea id="alamat_ktp" name="alamat_ktp" rows="3" placeholder="Alamat lengkap sesuai KTP" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">{{ old('alamat_ktp') }}</textarea>
            </div>

            <!-- TTL -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ttl (Tempat, Tanggal Lahir)</label>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Tempat Lahir" class="bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                </div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jenis Kelamin</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center justify-center p-3 rounded-lg border border-slate-800 bg-slate-950 hover:bg-slate-900/60 cursor-pointer transition-all">
                        <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin', 'Laki-laki') == 'Laki-laki' ? 'checked' : '' }} class="sr-only peer">
                        <span class="text-sm font-medium text-slate-400 peer-checked:text-amber-400">Laki-laki</span>
                    </label>
                    <label class="flex items-center justify-center p-3 rounded-lg border border-slate-800 bg-slate-950 hover:bg-slate-900/60 cursor-pointer transition-all">
                        <input type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }} class="sr-only peer">
                        <span class="text-sm font-medium text-slate-400 peer-checked:text-amber-400">Perempuan</span>
                    </label>
                </div>
            </div>

            <!-- Alamat E-Mail -->
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat E-Mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@domain.com" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>

            <!-- No. Hp Aktif -->
            <div class="space-y-1.5">
                <label for="no_hp" class="text-xs font-semibold uppercase tracking-wider text-slate-400">No. Hp Aktif</label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>

            <!-- No. Telp Perusahaan Aktif -->
            <div class="space-y-1.5">
                <label for="no_telp_perusahaan" class="text-xs font-semibold uppercase tracking-wider text-slate-400">No. Telp Perusahaan Aktif</label>
                <input type="text" id="no_telp_perusahaan" name="no_telp_perusahaan" value="{{ old('no_telp_perusahaan') }}" placeholder="Nomor Telepon Kantor/Perusahaan" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-100 placeholder-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>

            <!-- Jenis NPWP -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kategori Wajib Pajak (Jenis NPWP)</label>
                <div class="grid grid-cols-1 gap-2.5">
                    <label class="flex items-center justify-between p-3 rounded-lg border border-slate-800 bg-slate-950 hover:bg-slate-900/60 cursor-pointer transition-all">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="jenis_npwp" value="Orang Pribadi" {{ old('jenis_npwp', 'Orang Pribadi') == 'Orang Pribadi' ? 'checked' : '' }} class="sr-only peer">
                            <span class="text-sm font-medium text-slate-400 peer-checked:text-amber-400">Orang Pribadi</span>
                        </div>
                        <span class="text-[10px] text-slate-600 italic">Untuk Individu / Pekerja Mandiri</span>
                    </label>
                    <label class="flex items-center justify-between p-3 rounded-lg border border-slate-800 bg-slate-950 hover:bg-slate-900/60 cursor-pointer transition-all">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="jenis_npwp" value="Badan" {{ old('jenis_npwp') == 'Badan' ? 'checked' : '' }} class="sr-only peer">
                            <span class="text-sm font-medium text-slate-400 peer-checked:text-amber-400">Badan Usaha / Korporasi</span>
                        </div>
                        <span class="text-[10px] text-slate-600 italic">PT, CV, Koperasi, Yayasan</span>
                    </label>
                    <label class="flex items-center justify-between p-3 rounded-lg border border-slate-800 bg-slate-950 hover:bg-slate-900/60 cursor-pointer transition-all">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="jenis_npwp" value="BUT" {{ old('jenis_npwp') == 'BUT' ? 'checked' : '' }} class="sr-only peer">
                            <span class="text-sm font-medium text-slate-400 peer-checked:text-amber-400">Bentuk Usaha Tetap (BUT)</span>
                        </div>
                        <span class="text-[10px] text-slate-600 italic">Perusahaan Asing dengan operasional di Indonesia</span>
                    </label>
                </div>
            </div>

            <!-- Kependudukan -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kependudukan</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center justify-center p-3 rounded-lg border border-slate-800 bg-slate-950 hover:bg-slate-900/60 cursor-pointer transition-all">
                        <input type="radio" name="kependudukan" value="Dalam Negeri" {{ old('kependudukan', 'Dalam Negeri') == 'Dalam Negeri' ? 'checked' : '' }} class="sr-only peer">
                        <span class="text-sm font-medium text-slate-400 peer-checked:text-amber-400">Dalam Negeri</span>
                    </label>
                    <label class="flex items-center justify-center p-3 rounded-lg border border-slate-800 bg-slate-950 hover:bg-slate-900/60 cursor-pointer transition-all">
                        <input type="radio" name="kependudukan" value="Luar Negeri" {{ old('kependudukan') == 'Luar Negeri' ? 'checked' : '' }} class="sr-only peer">
                        <span class="text-sm font-medium text-slate-400 peer-checked:text-amber-400">Luar Negeri</span>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-800">
                <a href="{{ route('registrations.index') }}" class="px-5 py-2 rounded-lg border border-slate-800 text-slate-400 hover:text-slate-200 hover:bg-slate-900 transition-all font-medium text-xs">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg text-slate-950 font-bold text-xs bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 rounded-lg shadow-md shadow-amber-500/10 transition-all hover:scale-[1.01]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Styling to show borders when checked, since we hid native radio buttons with sr-only */
    input[type="radio"]:checked + span {
        color: #fbbf24; /* amber-400 */
    }
    label:has(input[type="radio"]:checked) {
        border-color: #d97706; /* amber-600 */
        background-color: rgb(217 119 6 / 0.05); /* amber-600/5 */
    }
</style>
@endsection
