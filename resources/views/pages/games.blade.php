@extends('layouts.admin')

@section('title', 'Sesi Permainan')
@section('header', 'Operasi Game')

@section('content')
<div class="mb-6 border-b border-gray-200 bg-white px-4 rounded-t-lg">
    <nav class="-mb-px flex space-x-8">
        <button onclick="switchTab('sessions')" id="tab-sessions"
            class="tab-btn border-indigo-500 text-indigo-600 py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fa-solid fa-history mr-2"></i> Riwayat Sesi
        </button>
        <button onclick="switchTab('leaderboard')" id="tab-leaderboard"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fa-solid fa-trophy mr-2"></i> Peringkat Global
        </button>
    </nav>
</div>

<div id="games-content" class="bg-white rounded-b-lg shadow p-6 min-h-[400px]">
    <div class="loader"></div>
</div>

<div id="session-modal"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50 flex overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 my-10 relative flex flex-col max-h-[90vh]">

        <div class="flex justify-between items-center border-b px-6 py-4 bg-gray-50 rounded-t-lg">
            <div>
                <h3 class="text-xl font-bold text-gray-800" id="modal-title">Detail Sesi</h3>
                <p class="text-sm text-gray-500" id="modal-subtitle">ID: -</p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 space-y-6" id="modal-body">
            <div class="loader"></div>
        </div>

        <div class="border-t px-6 py-4 bg-gray-50 rounded-b-lg text-right">
            <button onclick="closeModal()"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm font-bold">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/games.js') }}"></script>
@endpush