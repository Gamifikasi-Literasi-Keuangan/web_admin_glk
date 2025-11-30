@extends('layouts.admin')

@section('title', 'Pengaturan Game')
@section('header', 'Konfigurasi Sistem')

@section('content')
<div class="mb-6 border-b border-gray-200 bg-white px-4 rounded-t-lg">
    <nav class="-mb-px flex space-x-8">
        <button onclick="switchTab('config')" id="tab-config"
            class="tab-btn border-indigo-500 text-indigo-600 py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fa-solid fa-sliders mr-2"></i> Config Global
        </button>
        <button onclick="switchTab('tiles')" id="tab-tiles"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fa-solid fa-map mr-2"></i> Peta Papan
        </button>
        <button onclick="switchTab('interventions')" id="tab-interventions"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fa-solid fa-robot mr-2"></i> Intervensi AI
        </button>
    </nav>
</div>

<div id="settings-content" class="bg-white rounded-b-lg shadow p-6 min-h-[400px]">
    <div class="loader"></div>
</div>

<div id="tile-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50 flex">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-times"></i>
        </button>
        <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Kotak (Tile)</h3>
        <div id="modal-body">
            <div class="loader"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/settings.js') }}"></script>
@endpush