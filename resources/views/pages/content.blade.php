@extends('layouts.admin')

@section('title', 'Manajemen Konten')
@section('header', 'Pustaka Konten Game')

@section('content')
    <div class="mb-8">
        <!-- Tab Header -->
        <div class="bg-gradient-to-r from-zinc-50 to-zinc-100 border border-zinc-200 rounded-lg p-2 shadow-sm">
            <nav class="flex space-x-2 overflow-x-auto min-w-max">
                <button onclick="switchTab('profiling')" id="tab-profiling"
                    class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-green-500 text-white shadow-md whitespace-nowrap">
                    <i class="fa-solid fa-user-check mr-2"></i> Profiling
                </button>
                <button onclick="switchTab('scenarios')" id="tab-scenarios"
                    class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300 whitespace-nowrap">
                    <i class="fa-solid fa-scroll mr-2"></i> Skenario Game
                </button>
                <button onclick="switchTab('risk')" id="tab-risk"
                    class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300 whitespace-nowrap">
                    <i class="fa-solid fa-bolt mr-2"></i> Risiko
                </button>
                <button onclick="switchTab('chance')" id="tab-chance"
                    class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300 whitespace-nowrap">
                    <i class="fa-solid fa-gift mr-2"></i> Kesempatan
                </button>
                <button onclick="switchTab('quiz')" id="tab-quiz"
                    class="tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300 whitespace-nowrap">
                    <i class="fa-solid fa-graduation-cap mr-2"></i> Kuis
                </button>
            </nav>
        </div>
    </div>

    <div id="content-area">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="relative w-full md:max-w-sm">
                <input type="text" id="searchInput" oninput="handleSearch(this.value)"
                    class="w-full py-3 pl-4 pr-12 text-zinc-700 bg-white border border-zinc-300 rounded-lg focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100 shadow-sm transition-all"
                    placeholder="Cari konten...">
                <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-zinc-400">
                    <i class="fa-solid fa-search"></i>
                </span>
            </div>
            <button onclick="openCreateModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-sm font-semibold transition-colors flex items-center gap-2 hover:shadow-md">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Konten</span>
            </button>
        </div>

        <div id="table-wrapper" class="bg-white border border-zinc-200 rounded-lg shadow-sm overflow-hidden min-h-[400px]">
            <div class="flex items-center justify-center py-16">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mb-3"></div>
                    <p class="text-zinc-500">Memuat data konten...</p>
                </div>
            </div>
        </div>
    </div>

    <div id="detail-modal" class="fixed inset-0 bg-zinc-900 bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 flex p-4">
        <div class="bg-white border border-zinc-200 rounded-lg shadow-xl w-full max-w-3xl relative flex flex-col max-h-[90vh]">

            <div class="flex justify-between items-center border-b border-zinc-200 px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-lg shrink-0">
                <div class="flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fa-solid fa-info-circle text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-800" id="modal-title">Detail Konten</h3>
                </div>
                <button onclick="closeModal()"
                    class="bg-zinc-100 hover:bg-zinc-200 text-zinc-600 hover:text-zinc-800 p-2 rounded-lg transition-colors">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar" id="modal-body">
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                    <p class="text-zinc-500 mt-2">Memuat detail...</p>
                </div>
            </div>

            <div class="border-t border-zinc-200 px-6 py-4 bg-zinc-50 rounded-b-lg flex justify-between items-center shrink-0">
                <button onclick="deleteContent()" id="deleteBtn"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors hidden shadow-sm">
                    <i class="fa-solid fa-trash mr-2"></i> Hapus
                </button>
                <div class="flex gap-3">
                    <button onclick="openEditModal()" id="editBtn"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors hidden shadow-sm">
                        <i class="fa-solid fa-edit mr-2"></i> Edit
                    </button>
                    <button onclick="closeModal()"
                        class="bg-zinc-300 hover:bg-zinc-400 text-zinc-800 px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    <div id="form-modal" class="fixed inset-0 bg-zinc-900 bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 flex p-4">
        <div class="bg-white border border-zinc-200 rounded-lg shadow-xl w-full max-w-4xl relative flex flex-col max-h-[90vh]">
            <div class="flex justify-between items-center border-b border-zinc-200 px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-lg shrink-0">
                <div class="flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fa-solid fa-plus-circle text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-800" id="form-modal-title">Tambah Konten</h3>
                </div>
                <button onclick="closeFormModal()"
                    class="bg-zinc-100 hover:bg-zinc-200 text-zinc-600 hover:text-zinc-800 p-2 rounded-lg transition-colors">&times;</button>
            </div>

            <form id="contentForm" onsubmit="handleSubmit(event)" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1" id="form-body">
                    <!-- Form fields akan di-generate oleh JavaScript -->
                </div>

                <div class="border-t border-zinc-200 px-6 py-4 bg-zinc-50 rounded-b-lg text-right shrink-0">
                    <button type="button" onclick="closeFormModal()"
                        class="bg-zinc-300 hover:bg-zinc-400 text-zinc-800 px-6 py-2 rounded-lg text-sm font-semibold transition-colors mr-3 shadow-sm">Batal</button>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                        <i class="fa-solid fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/content.js') }}"></script>
@endpush