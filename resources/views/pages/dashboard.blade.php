@extends('layouts.admin')

@section('title', 'Overview')
@section('header', 'Ringkasan Sistem')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500 flex items-center">
        <div class="p-4 rounded-full bg-blue-50 text-blue-500">
            <i class="fa-solid fa-users text-3xl"></i>
        </div>
        <div class="ml-6">
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Pemain</p>
            <h3 class="text-3xl font-bold text-gray-800" id="stat-players">-</h3>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500 flex items-center">
        <div class="p-4 rounded-full bg-green-50 text-green-500">
            <i class="fa-solid fa-gamepad text-3xl"></i>
        </div>
        <div class="ml-6">
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Sesi Aktif</p>
            <h3 class="text-3xl font-bold text-gray-800" id="stat-sessions">-</h3>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500 flex items-center">
        <div class="p-4 rounded-full bg-purple-50 text-purple-500">
            <i class="fa-solid fa-brain text-3xl"></i>
        </div>
        <div class="ml-6">
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Keputusan</p>
            <h3 class="text-3xl font-bold text-gray-800" id="stat-decisions">-</h3>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-8 text-center">
    <img src="https://cdn-icons-png.flaticon.com/512/2942/2942813.png" alt="Dashboard"
        class="w-32 h-32 mx-auto mb-4 opacity-80">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang di Panel Admin Gamifikasi!</h2>
    <p class="text-gray-600 max-w-2xl mx-auto">
        Sistem ini membantu Anda memantau perkembangan literasi keuangan pemain secara real-time.
        Gunakan menu di samping untuk mengelola konten, memantau pemain, atau melihat analisis mendalam.
    </p>

    <div class="mt-8 flex justify-center gap-4">
        <a href="{{ route('admin.players') }}"
            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow">
            <i class="fa-solid fa-users mr-2"></i> Kelola Pemain
        </a>
        <a href="{{ route('admin.content') }}"
            class="px-6 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition">
            <i class="fa-solid fa-book mr-2"></i> Kelola Konten
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endpush