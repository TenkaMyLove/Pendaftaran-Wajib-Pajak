@extends('layouts.app')

@section('title', 'Daftar Pendaftar Wajib Pajak')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-100">Daftar Pendaftaran Wajib Pajak</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar lengkap pendaftar wajib pajak yang tercatat di database.</p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            @if($registrations->isEmpty())
                <div class="p-12 text-center text-slate-500 space-y-4">
                    <div class="mx-auto h-12 w-12 rounded-full bg-slate-950 flex items-center justify-center border border-slate-800">
                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="font-medium text-slate-300 text-sm">Belum ada data pendaftar</p>
                        <p class="text-xs text-slate-500">Klik "Daftar Baru" di menu atas untuk menambahkan pendaftar pertama.</p>
                    </div>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-900/40">
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">NPWP / Nama</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">No. KTP / TTL</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">Kontak</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">Kategori & Status</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">Terdaftar</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">Status Verifikasi</th>
                            <th class="px-5 py-4 text-xs font-semibold uppercase text-slate-400 tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40">
                        @foreach($registrations as $reg)
                            <tr class="hover:bg-slate-900/30 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-medium text-sm text-slate-200 tracking-tight">{{ $reg->npwp }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $reg->nama_lengkap }} ({{ $reg->jenis_kelamin }})</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-xs text-slate-300 font-mono">{{ $reg->no_ktp }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $reg->tempat_lahir }}, {{ \Carbon\Carbon::parse($reg->tanggal_lahir)->translatedFormat('d F Y') }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-xs text-slate-300">{{ $reg->email }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">HP: {{ $reg->no_hp }} | Kantor: {{ $reg->no_telp_perusahaan }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-950 text-amber-300 border border-slate-800">
                                            @if($reg->jenis_npwp == 'Badan')
                                                Badan Usaha
                                            @elseif($reg->jenis_npwp == 'BUT')
                                                Bentuk Usaha Tetap (BUT)
                                            @else
                                                {{ $reg->jenis_npwp }}
                                            @endif
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $reg->kependudukan == 'Dalam Negeri' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                            {{ $reg->kependudukan }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500">
                                    {{ $reg->created_at->diffForHumans() }}
                                </td>
                                <td class="px-5 py-4">
                                    @if($reg->status == 'Verified')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Terverifikasi
                                        </span>
                                    @elseif($reg->status == 'Rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            Menunggu Verifikasi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if($reg->status == 'Pending')
                                        <div class="flex items-center justify-end space-x-2">
                                            <form action="{{ route('registrations.updateStatus', $reg) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Verified">
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white transition-all">Verifikasi</button>
                                            </form>
                                            <form action="{{ route('registrations.updateStatus', $reg) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-600 hover:bg-rose-500 text-white transition-all">Tolak</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-500 font-mono">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
