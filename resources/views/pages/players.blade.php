@extends('layouts.admin')

@section('title', 'Profiling Player')
@section('header', 'Profiling Player')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #27272a;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #52525b;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #71717a;
    }

    .loader {
        border: 4px solid #27272a;
        border-top: 4px solid #10b981;
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

    /* Modal animations */
    .modal-enter {
        animation: modalFadeIn 0.3s ease-out;
    }

    .modal-exit {
        animation: modalFadeOut 0.3s ease-in;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes modalFadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.9);
        }
    }

    /* Button hover effects */
    .action-button {
        transition: all 0.2s ease;
    }

    .action-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Tab styling */
    .tab-btn {
        @apply text-zinc-400 hover:text-white hover:bg-zinc-700;
    }

    .tab-btn.active {
        @apply bg-green-600 text-white;
    }

    .tab-panel {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Enhanced scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #27272a;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #52525b;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #71717a;
    }

    /* Gradient backgrounds */
    .gradient-bg-blue {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05));
    }

    .gradient-bg-purple {
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(126, 34, 206, 0.05));
    }

    .gradient-bg-green {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(21, 128, 61, 0.05));
    }
</style>

<div class="w-full">
    <div class="max-w-7xl mx-auto px-3 md:px-6">
        
        <div id="player-container">
            <!-- Search Bar -->
            <div class="mb-6 md:mb-8">
                <div class="relative w-full sm:w-80 md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 md:pl-4">
                        <i class="fa-solid fa-search text-gray-600"></i>
                    </span>
                    <input type="text" id="searchInput" oninput="handleSearch(this.value)"
                        class="w-full h-10 md:h-12 py-2 pl-10 md:pl-12 pr-4 text-gray-800 bg-zinc-200 border border-zinc-400 rounded-xl md:rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 placeholder-gray-600 font-['Poppins'] text-sm md:text-base"
                        placeholder="Cari Username Player">
                </div>
            </div>

            <!-- Table Content -->
            <div id="table-wrapper">
                <div class="flex justify-center items-center py-8 md:py-12">
                    <div class="loader"></div>
                </div>
            </div>
        </div>

        <!-- Detail Section -->
        <div id="detail-wrapper" class="hidden"></div>
    </div>
</div>

<!-- Ban Player Modal -->
<div id="ban-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 flex overflow-y-auto backdrop-blur-sm px-4">
    <div class="bg-zinc-800 rounded-xl md:rounded-2xl shadow-2xl w-full max-w-md my-10 relative border border-zinc-700">
        <div class="flex justify-between items-center border-b border-zinc-700 px-4 md:px-6 py-3 md:py-4 bg-red-600/20 rounded-t-xl md:rounded-t-2xl">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-white font-['Poppins']">Ban Player</h3>
                <p class="text-red-300 text-xs md:text-sm font-['Poppins']">Konfirmasi tindakan</p>
            </div>
            <button onclick="closeBanModal()" class="text-zinc-400 hover:text-white text-xl md:text-2xl font-bold transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 md:p-6">
            <div class="text-center mb-4 md:mb-6">
                <div class="mx-auto flex items-center justify-center h-10 w-10 md:h-12 md:w-12 rounded-full bg-red-600/20 mb-3 md:mb-4">
                    <i class="fas fa-ban text-red-400 text-lg md:text-xl"></i>
                </div>
                <p class="text-white text-base md:text-lg font-semibold font-['Poppins'] mb-2">Yakin ingin memblokir player ini?</p>
                <p class="text-zinc-400 text-xs md:text-sm font-['Poppins']" id="ban-player-name">Player akan tidak dapat mengakses sistem</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="closeBanModal()" class="flex-1 bg-zinc-700 hover:bg-zinc-600 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-colors text-sm md:text-base">
                    Batal
                </button>
                <button onclick="confirmBanPlayer()" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-colors text-sm md:text-base">
                    <i class="fas fa-ban mr-2"></i>Ban Player
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Unban Player Modal -->
<div id="unban-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 flex overflow-y-auto backdrop-blur-sm px-4">
    <div class="bg-zinc-800 rounded-xl md:rounded-2xl shadow-2xl w-full max-w-md my-10 relative border border-zinc-700">
        <div class="flex justify-between items-center border-b border-zinc-700 px-4 md:px-6 py-3 md:py-4 bg-green-600/20 rounded-t-xl md:rounded-t-2xl">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-white font-['Poppins']">Unban Player</h3>
                <p class="text-green-300 text-xs md:text-sm font-['Poppins']">Konfirmasi tindakan</p>
            </div>
            <button onclick="closeUnbanModal()" class="text-zinc-400 hover:text-white text-xl md:text-2xl font-bold transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 md:p-6">
            <div class="text-center mb-4 md:mb-6">
                <div class="mx-auto flex items-center justify-center h-10 w-10 md:h-12 md:w-12 rounded-full bg-green-600/20 mb-3 md:mb-4">
                    <i class="fas fa-user-check text-green-400 text-lg md:text-xl"></i>
                </div>
                <p class="text-white text-base md:text-lg font-semibold font-['Poppins'] mb-2">Yakin ingin membatalkan ban player ini?</p>
                <p class="text-zinc-400 text-xs md:text-sm font-['Poppins']" id="unban-player-name">Player akan dapat mengakses sistem kembali</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="closeUnbanModal()" class="flex-1 bg-zinc-700 hover:bg-zinc-600 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-colors text-sm md:text-base">
                    Batal
                </button>
                <button onclick="confirmUnbanPlayer()" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-colors text-sm md:text-base">
                    <i class="fas fa-user-check mr-2"></i>Unban Player
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Player Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 flex overflow-y-auto backdrop-blur-sm px-4">
    <div class="bg-zinc-800 rounded-xl md:rounded-2xl shadow-2xl w-full max-w-md my-10 relative border border-zinc-700">
        <div class="flex justify-between items-center border-b border-zinc-700 px-4 md:px-6 py-3 md:py-4 bg-yellow-600/20 rounded-t-xl md:rounded-t-2xl">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-white font-['Poppins']">Hapus Player</h3>
                <p class="text-yellow-300 text-xs md:text-sm font-['Poppins']">Tindakan tidak dapat dibatalkan</p>
            </div>
            <button onclick="closeDeleteModal()" class="text-zinc-400 hover:text-white text-xl md:text-2xl font-bold transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 md:p-6">
            <div class="text-center mb-4 md:mb-6">
                <div class="mx-auto flex items-center justify-center h-10 w-10 md:h-12 md:w-12 rounded-full bg-yellow-600/20 mb-3 md:mb-4">
                    <i class="fas fa-trash text-yellow-400 text-lg md:text-xl"></i>
                </div>
                <p class="text-white text-base md:text-lg font-semibold font-['Poppins'] mb-2">Yakin ingin menghapus player ini?</p>
                <p class="text-zinc-400 text-xs md:text-sm font-['Poppins']" id="delete-player-name">Semua data player akan hilang permanen</p>
                <div class="mt-3 md:mt-4 bg-red-900/50 border border-red-600/50 rounded-lg p-2 md:p-3">
                    <p class="text-red-300 text-xs font-['Poppins']"><i class="fas fa-exclamation-triangle mr-1"></i>Peringatan: Data yang sudah dihapus tidak dapat dikembalikan!</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 bg-zinc-700 hover:bg-zinc-600 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-colors text-sm md:text-base">
                    Batal
                </button>
                <button onclick="confirmDeletePlayer()" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-colors text-sm md:text-base">
                    <i class="fas fa-trash mr-2"></i>Hapus Player
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 flex px-4">
    <div class="bg-zinc-800 rounded-xl md:rounded-2xl p-6 md:p-8 border border-zinc-700">
        <div class="text-center">
            <div class="loader mb-3 md:mb-4"></div>
            <p class="text-white font-['Poppins'] text-sm md:text-base" id="loading-text">Memproses...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/players.js') }}?v={{ time() }}" defer></script>
@endpush