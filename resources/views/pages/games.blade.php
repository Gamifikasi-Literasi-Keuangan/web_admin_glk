@extends('layouts.admin')

@section('title', 'Sesi Permainan')
@section('header', 'Session Game')

@section('content')
<div class="w-full relative overflow-hidden min-h-[600px]">
    <!-- Game Sessions Grid Container -->
    <div class="max-w-7xl mx-auto px-6">      
        <div id="games-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-5xl mx-auto" id="session-grid">
                <!-- Loading State -->
                <div class="col-span-full flex justify-center items-center py-12">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Template for Session Card -->
    <div id="session-card-template" class="hidden">
        <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all duration-300 hover:-translate-y-1 session-card">
            <!-- Session Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-gray-900 text-lg font-bold font-['Poppins'] mb-1">Session Info</h3>
                    <p class="text-gray-600 text-sm font-['Poppins']">Game Session Details</p>
                </div>
                <div class="session-status-badge px-3 py-1 rounded-full text-xs font-semibold">
                    <span class="session-status-text">Active</span>
                </div>
            </div>
            
            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>
            
            <!-- Session Details -->
            <div class="space-y-3">
                <div>
                    <p class="text-gray-600 text-sm font-['Poppins'] mb-1">Session ID</p>
                    <p class="text-gray-900 text-xl font-bold font-['Poppins'] session-id-value">GAME-001</p>
                </div>
                
                <!-- Action Button -->
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold font-['Poppins'] transition-colors session-detail-btn">
                    View Details
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Session Detail Modal -->
<div id="session-modal"
    class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 flex overflow-y-auto backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 my-10 relative flex flex-col max-h-[90vh] border border-gray-200">

        <div class="flex justify-between items-center border-b px-8 py-6 bg-gradient-to-r from-green-600 to-green-700 rounded-t-2xl">
            <div>
                <h3 class="text-2xl font-bold text-white font-['Poppins']" id="modal-title">Session Details</h3>
                <p class="text-green-100 text-sm font-['Poppins']" id="modal-subtitle">ID: -</p>
            </div>
            <button onclick="closeModal()" class="text-white hover:text-green-200 text-3xl font-bold transition-colors p-2 hover:bg-white/10 rounded-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-8 overflow-y-auto flex-1 space-y-6 bg-gray-800" id="modal-body">
            <div class="flex justify-center items-center py-12">
                <div class="loader"></div>
            </div>
        </div>

        <div class="border-t px-8 py-6 bg-gradient-to-r from-green-600 to-green-700 rounded-b-2xl text-right">
            <button onclick="closeModal()"
                class="bg-white/20 hover:bg-white/30 text-white px-8 py-3 rounded-xl text-base font-semibold font-['Poppins'] transition-all duration-200 backdrop-blur-sm border border-white/20">
                <i class="fas fa-times mr-2"></i>Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.session-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.session-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.session-status-badge.active {
    background: linear-gradient(45deg, #16a34a, #22c55e);
}

.session-status-badge.inactive {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
}

.loader {
    border: 3px solid #52525b;
    border-top: 3px solid #10b981;
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

/* Custom scrollbar for modal */
#modal-body::-webkit-scrollbar {
    width: 6px;
}

#modal-body::-webkit-scrollbar-track {
    background: #27272a;
}

#modal-body::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 3px;
}

#modal-body::-webkit-scrollbar-thumb:hover {
    background: #059669;
}

/* Enhanced sidebar styles */
.sidebar-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar-item:hover {
    background: rgba(139, 92, 246, 0.1);
    transform: translateX(4px);
}

.sidebar-item.active,
.sidebar-item:hover {
    box-shadow: inset 4px 0 0 #10b981;
}

/* Loading animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s infinite;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/admin/games.js') }}"></script>
@endpush