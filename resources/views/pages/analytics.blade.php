@extends('layouts.admin')

@section('title', 'Analitik & Laporan')
@section('header', 'Data Intelligence')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mb-6 border-b border-gray-200 bg-white px-4 rounded-t-lg shadow-sm overflow-x-auto">
    <nav class="-mb-px flex space-x-6">
        <button onclick="switchTab('business')" id="tab-business"
            class="tab-btn border-indigo-500 text-indigo-600 py-4 px-2 border-b-2 font-medium text-sm whitespace-nowrap">
            <i class="fa-solid fa-briefcase mr-2"></i> Bisnis
        </button>
        <button onclick="switchTab('learning')" id="tab-learning"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 py-4 px-2 border-b-2 font-medium text-sm whitespace-nowrap">
            <i class="fa-solid fa-graduation-cap mr-2"></i> Pembelajaran
        </button>
        <button onclick="switchTab('behavior')" id="tab-behavior"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 py-4 px-2 border-b-2 font-medium text-sm whitespace-nowrap">
            <i class="fa-solid fa-user-clock mr-2"></i> Perilaku
        </button>
        <button onclick="switchTab('content')" id="tab-content"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 py-4 px-2 border-b-2 font-medium text-sm whitespace-nowrap">
            <i class="fa-solid fa-book mr-2"></i> Performa Konten
        </button>
        <button onclick="switchTab('visual')" id="tab-visual"
            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 py-4 px-2 border-b-2 font-medium text-sm whitespace-nowrap">
            <i class="fa-solid fa-map mr-2"></i> Visual
        </button>
    </nav>
</div>

<div id="analytics-content" class="min-h-[500px]">
    <div class="loader"></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/analytics.js') }}"></script>
@endpush