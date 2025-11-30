@extends('layouts.admin')

@section('title', 'Manajemen Konten')
@section('header', 'Pustaka Konten Game')

@section('content')
<div class="mb-6 border-b border-gray-200 bg-white px-4 pt-2 rounded-t-lg shadow-sm overflow-x-auto">
    <nav class="-mb-px flex space-x-8 min-w-max" aria-label="Tabs">
        <button onclick="switchTab('scenarios')" id="tab-scenarios"
            class="tab-btn border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            <i class="fa-solid fa-scroll mr-2"></i> Skenario
        </button>
        <button onclick="switchTab('risk')" id="tab-risk"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            <i class="fa-solid fa-bolt mr-2"></i> Risiko
        </button>
        <button onclick="switchTab('chance')" id="tab-chance"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            <i class="fa-solid fa-gift mr-2"></i> Kesempatan
        </button>
        <button onclick="switchTab('quiz')" id="tab-quiz"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            <i class="fa-solid fa-graduation-cap mr-2"></i> Kuis
        </button>
    </nav>
</div>

<div id="content-area">
    <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="relative w-full md:max-w-sm">
            <input type="text" id="searchInput" oninput="handleSearch(this.value)"
                class="w-full py-2 pl-4 pr-10 text-gray-700 bg-white border rounded-lg focus:border-indigo-500 focus:outline-none shadow-sm"
                placeholder="Cari konten...">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                <i class="fa-solid fa-search"></i>
            </span>
        </div>
    </div>

    <div id="table-wrapper" class="bg-white rounded-lg shadow overflow-hidden min-h-[300px]">
        <div class="loader mt-10"></div>
    </div>
</div>

<div id="detail-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50 flex p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl relative flex flex-col max-h-[90vh]">

        <div class="flex justify-between items-center border-b px-6 py-4 bg-gray-50 rounded-t-lg shrink-0">
            <h3 class="text-xl font-bold text-gray-800" id="modal-title">Detail Konten</h3>
            <button onclick="closeModal()"
                class="text-gray-400 hover:text-red-500 transition-colors text-2xl focus:outline-none">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar" id="modal-body">
            <div class="loader"></div>
        </div>

        <div class="border-t px-6 py-3 bg-gray-50 rounded-b-lg text-right shrink-0">
            <button onclick="closeModal()"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm font-bold transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/content.js') }}"></script>
@endpush