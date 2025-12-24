@extends('layouts.admin')

@section('title', 'Manajemen Pemain')
@section('header', 'Daftar Pemain')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .loader {
        border: 4px solid #f3f4f6;
        border-top: 4px solid #4f46e5;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div id="player-container">
    <div class="mb-6 bg-white p-4 rounded-lg shadow flex justify-between items-center">
        <div class="relative w-full max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fa-solid fa-search text-gray-400"></i>
            </span>
            <input type="text" id="searchInput" oninput="handleSearch(this.value)"
                class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none"
                placeholder="Cari nama atau username...">
        </div>
        <button onclick="renderPlayerList()" class="text-gray-500 hover:text-blue-600 p-2" title="Refresh">
            <i class="fa-solid fa-sync"></i>
        </button>
    </div>

    <div id="table-wrapper"></div>
</div>

<div id="detail-wrapper" class="hidden"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/players.js') }}"></script>
@endpush