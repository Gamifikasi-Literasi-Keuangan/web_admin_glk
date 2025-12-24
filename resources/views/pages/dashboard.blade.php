@extends('layouts.admin')

@section('title', 'Overview')
@section('header', 'Ringkasan Sistem')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white border border-zinc-200 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow enhanced-shadow">
        <div class="flex items-center">
            <div class="bg-green-100 p-4 rounded-xl mr-6">
                <i class="fa-solid fa-users text-green-600 text-3xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-zinc-500 text-sm font-semibold uppercase tracking-wide mb-2">Total Pemain</p>
                <h3 class="text-3xl font-bold text-zinc-800" id="stat-players">-</h3>
                <div class="w-full bg-zinc-100 rounded-full h-2 mt-3">
                    <div class="bg-green-500 h-2 rounded-full w-3/4"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-zinc-200 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow enhanced-shadow">
        <div class="flex items-center">
            <div class="bg-emerald-100 p-4 rounded-xl mr-6">
                <i class="fa-solid fa-gamepad text-emerald-600 text-3xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-zinc-500 text-sm font-semibold uppercase tracking-wide mb-2">Sesi Aktif</p>
                <h3 class="text-3xl font-bold text-zinc-800" id="stat-sessions">-</h3>
                <div class="w-full bg-zinc-100 rounded-full h-2 mt-3">
                    <div class="bg-emerald-500 h-2 rounded-full w-1/2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-zinc-200 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow enhanced-shadow">
        <div class="flex items-center">
            <div class="bg-green-100 p-4 rounded-xl mr-6">
                <i class="fa-solid fa-brain text-green-600 text-3xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-zinc-500 text-sm font-semibold uppercase tracking-wide mb-2">Total Keputusan</p>
                <h3 class="text-3xl font-bold text-zinc-800" id="stat-decisions">-</h3>
                <div class="w-full bg-zinc-100 rounded-full h-2 mt-3">
                    <div class="bg-green-500 h-2 rounded-full w-5/6"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg shadow-sm p-8 text-center enhanced-shadow">
    <div class="bg-white p-6 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center shadow-md border border-zinc-200">
        <i class="fa-solid fa-chart-line text-green-600 text-4xl"></i>
    </div>
    
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-zinc-800 mb-4">Selamat Datang di Panel Admin</h2>
        <div class="bg-green-500 h-1 w-24 mx-auto rounded-full mb-4"></div>
        <h3 class="text-xl font-semibold text-green-700 mb-3">Gamifikasi Literasi Keuangan</h3>
        <p class="text-zinc-600 max-w-2xl mx-auto leading-relaxed">
            Sistem monitoring dan manajemen komprehensif untuk menganalisis perkembangan literasi keuangan pemain secara real-time.
            Kelola konten, pantau progres pemain, dan analisis data pembelajaran dengan mudah melalui menu navigasi di sebelah kiri.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endpush