@extends('layouts.admin')

@section('title', 'Pengaturan Game')
@section('header', 'Konfigurasi Sistem')

@section('content')
<div class="mb-8">
    <!-- Tab Header -->
    <div class="bg-gradient-to-r from-zinc-50 to-zinc-100 border border-zinc-200 rounded-lg p-2 shadow-sm">
        <nav class="flex space-x-2">
            <button onclick="switchTab('config')" id="tab-config"
                class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-green-500 text-white shadow-md">
                <div class="bg-white bg-opacity-20 p-1.5 rounded-full mr-3">
                    <i class="fa-solid fa-sliders text-white text-sm"></i>
                </div>
                <span>Config Global</span>
            </button>
            <button onclick="switchTab('tiles')" id="tab-tiles"
                class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300">
                <div class="bg-zinc-100 p-1.5 rounded-full mr-3">
                    <i class="fa-solid fa-map text-zinc-500 text-sm"></i>
                </div>
                <span>Peta Papan</span>
            </button>
            <button onclick="switchTab('interventions')" id="tab-interventions"
                class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300">
                <div class="bg-zinc-100 p-1.5 rounded-full mr-3">
                    <i class="fa-solid fa-robot text-zinc-500 text-sm"></i>
                </div>
                <span>Intervensi AI</span>
            </button>
        </nav>
    </div>
</div>

<div id="settings-content" class="bg-white border border-zinc-200 rounded-lg shadow-sm p-8 min-h-[500px]">
    <div class="flex items-center justify-center h-32">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mb-3"></div>
            <p class="text-zinc-500">Memuat data pengaturan...</p>
        </div>
    </div>
</div>

<div id="tile-modal" class="fixed inset-0 bg-zinc-900 bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 flex p-4">
    <div class="bg-white border border-zinc-200 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-auto relative">
        <div class="sticky top-0 bg-white border-b border-zinc-200 p-4 flex items-center justify-between rounded-t-lg">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded-lg mr-3">
                    <i class="fa-solid fa-map-marker-alt text-green-600"></i>
                </div>
                <h3 class="text-lg font-bold text-zinc-800">Detail Kotak (Tile)</h3>
            </div>
            <button onclick="closeModal()" class="bg-zinc-100 hover:bg-zinc-200 text-zinc-600 hover:text-zinc-800 p-2 rounded-lg transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div id="modal-body" class="p-6">
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                <p class="text-zinc-500 mt-2">Memuat data...</p>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/settings.js') }}"></script>
@endpush