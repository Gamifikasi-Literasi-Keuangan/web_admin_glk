@extends('layouts.admin')

@section('title', 'Analitik & Laporan')
@section('header', 'Data Intelligence')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mb-6 border-b border-zinc-200 bg-white px-4 rounded-t-lg shadow-md overflow-x-auto">
    <nav class="-mb-px flex space-x-6">
        <button onclick="switchTab('business')" id="tab-business"
            class="tab-btn border-green-500 text-green-600 bg-green-50 py-4 px-3 border-b-2 font-medium text-sm whitespace-nowrap rounded-t-lg transition-all duration-200">
            <i class="fa-solid fa-briefcase mr-2"></i> Bisnis
        </button>
        <button onclick="switchTab('learning')" id="tab-learning"
            class="tab-btn border-transparent text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50 py-4 px-3 border-b-2 font-medium text-sm whitespace-nowrap rounded-t-lg transition-all duration-200">
            <i class="fa-solid fa-graduation-cap mr-2"></i> Pembelajaran
        </button>
        <button onclick="switchTab('behavior')" id="tab-behavior"
            class="tab-btn border-transparent text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50 py-4 px-3 border-b-2 font-medium text-sm whitespace-nowrap rounded-t-lg transition-all duration-200">
            <i class="fa-solid fa-user-clock mr-2"></i> Perilaku
        </button>
        <button onclick="switchTab('content')" id="tab-content"
            class="tab-btn border-transparent text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50 py-4 px-3 border-b-2 font-medium text-sm whitespace-nowrap rounded-t-lg transition-all duration-200">
            <i class="fa-solid fa-book mr-2"></i> Performa Konten
        </button>
        <button onclick="switchTab('visual')" id="tab-visual"
            class="tab-btn border-transparent text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50 py-4 px-3 border-b-2 font-medium text-sm whitespace-nowrap rounded-t-lg transition-all duration-200">
            <i class="fa-solid fa-map mr-2"></i> Visual
        </button>
    </nav>
</div>

<div id="analytics-content" class="min-h-[500px] bg-zinc-50 rounded-lg p-1">
    <div class="flex items-center justify-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/analytics.js') }}"></script>
@endpush