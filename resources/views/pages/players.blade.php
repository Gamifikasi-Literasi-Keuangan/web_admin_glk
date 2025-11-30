@extends('layouts.admin')

@section('title', 'Manajemen Pemain')
@section('header', 'Daftar Pemain')

@section('content')
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