<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Training ANN - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-6">
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Data Training ANN</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Kelola dataset untuk training model Artificial Neural Network
                        </p>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Data</dt>
                                            <dd class="text-2xl font-semibold text-gray-900" id="total-data">0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                            <span class="text-red-600 font-semibold">N</span>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Novice</dt>
                                            <dd class="text-2xl font-semibold text-gray-900" id="novice-count">0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <span class="text-yellow-600 font-semibold">E</span>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Explorer</dt>
                                            <dd class="text-2xl font-semibold text-gray-900" id="explorer-count">0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                            <span class="text-green-600 font-semibold">B</span>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Builder</dt>
                                            <dd class="text-2xl font-semibold text-gray-900" id="builder-count">0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <span class="text-purple-600 font-semibold">A+</span>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Architect + Sage</dt>
                                            <dd class="text-2xl font-semibold text-gray-900" id="advanced-count">0</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div class="flex-1 w-full sm:w-auto">
                                    <input type="text" id="search-input" placeholder="Cari berdasarkan cluster atau notes..." 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex gap-3 w-full sm:w-auto">
                                    <button onclick="openAddModal()" 
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah Data
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tabungan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Investasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asuransi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cluster</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="data-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be inserted here by JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-gray-700">
                                    Showing <span id="showing-from">0</span> to <span id="showing-to">0</span> of <span id="total-records">0</span> entries
                                </div>
                                <div id="pagination-controls" class="flex gap-2">
                                    <!-- Pagination buttons will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit -->
    <div id="dataModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-900">Tambah Data Training</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="dataForm" class="mt-4">
                <input type="hidden" id="data-id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pendapatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pendapatan</label>
                        <select id="pendapatan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Anggaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anggaran</label>
                        <select id="anggaran" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Tabungan & Dana Darurat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tabungan & Dana Darurat</label>
                        <select id="tabungan_dan_dana_darurat" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Utang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Utang</label>
                        <select id="utang" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Investasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Investasi</label>
                        <select id="investasi" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Asuransi & Proteksi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Asuransi & Proteksi</label>
                        <select id="asuransi_dan_proteksi" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Tujuan Jangka Panjang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Jangka Panjang</label>
                        <select id="tujuan_jangka_panjang" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Cluster -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cluster</label>
                        <select id="cluster" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Opsional)</label>
                        <textarea id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = '/api/admin/training-data';
        const TOKEN = localStorage.getItem('admin_token');
        let currentPage = 1;
        let optionsData = null;

        // Load options on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadOptions();
            loadData();
            
            // Search functionality
            document.getElementById('search-input').addEventListener('input', debounce(function() {
                currentPage = 1;
                loadData();
            }, 500));
        });

        async function loadOptions() {
            try {
                const response = await fetch(`${API_BASE}/options`, {
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    optionsData = result.data;
                    populateSelectOptions();
                }
            } catch (error) {
                console.error('Error loading options:', error);
            }
        }

        function populateSelectOptions() {
            if (!optionsData) return;
            
            // Populate level selects
            const levelFields = ['pendapatan', 'anggaran', 'tabungan_dan_dana_darurat', 'utang', 'investasi', 'asuransi_dan_proteksi', 'tujuan_jangka_panjang'];
            levelFields.forEach(field => {
                const select = document.getElementById(field);
                select.innerHTML = '<option value="">Pilih...</option>';
                optionsData.levels.forEach(level => {
                    select.innerHTML += `<option value="${level}">${level}</option>`;
                });
            });
            
            // Populate cluster select
            const clusterSelect = document.getElementById('cluster');
            clusterSelect.innerHTML = '<option value="">Pilih...</option>';
            optionsData.clusters.forEach(cluster => {
                clusterSelect.innerHTML += `<option value="${cluster}">${cluster}</option>`;
            });
        }

        async function loadData() {
            try {
                const search = document.getElementById('search-input').value;
                const url = new URL(API_BASE, window.location.origin);
                url.searchParams.append('page', currentPage);
                url.searchParams.append('per_page', 15);
                if (search) url.searchParams.append('search', search);
                
                const response = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    renderTable(result.data.data);
                    renderPagination(result.data);
                    updateStatistics(result.distribution, result.data.total);
                }
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Gagal memuat data');
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('data-table-body');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="11" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td></tr>';
                return;
            }
            
            data.forEach(item => {
                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.pendapatan}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.anggaran}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.tabungan_dan_dana_darurat}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.utang}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.investasi}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.asuransi_dan_proteksi}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.tujuan_jangka_panjang}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${getClusterBadge(item.cluster)}">${item.cluster}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${item.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${item.is_active ? 'Aktif' : 'Tidak Aktif'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="editData(${item.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button onclick="deleteData(${item.id})" class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function getClusterBadge(cluster) {
            const badges = {
                'Financial Novice': 'bg-red-100 text-red-800',
                'Financial Explorer': 'bg-yellow-100 text-yellow-800',
                'Foundation Builder': 'bg-green-100 text-green-800',
                'Financial Architect': 'bg-blue-100 text-blue-800',
                'Financial Sage': 'bg-purple-100 text-purple-800'
            };
            return badges[cluster] || 'bg-gray-100 text-gray-800';
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination-controls');
            container.innerHTML = '';
            
            document.getElementById('showing-from').textContent = data.from || 0;
            document.getElementById('showing-to').textContent = data.to || 0;
            document.getElementById('total-records').textContent = data.total;
            
            if (data.last_page <= 1) return;
            
            // Previous button
            if (data.current_page > 1) {
                container.innerHTML += `<button onclick="changePage(${data.current_page - 1})" class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Prev</button>`;
            }
            
            // Page numbers
            for (let i = 1; i <= data.last_page; i++) {
                if (i === data.current_page) {
                    container.innerHTML += `<button class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm">${i}</button>`;
                } else if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                    container.innerHTML += `<button onclick="changePage(${i})" class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">${i}</button>`;
                } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                    container.innerHTML += `<span class="px-2">...</span>`;
                }
            }
            
            // Next button
            if (data.current_page < data.last_page) {
                container.innerHTML += `<button onclick="changePage(${data.current_page + 1})" class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Next</button>`;
            }
        }

        function updateStatistics(distribution, total) {
            document.getElementById('total-data').textContent = total;
            document.getElementById('novice-count').textContent = distribution['Financial Novice'] || 0;
            document.getElementById('explorer-count').textContent = distribution['Financial Explorer'] || 0;
            document.getElementById('builder-count').textContent = distribution['Foundation Builder'] || 0;
            const advanced = (distribution['Financial Architect'] || 0) + (distribution['Financial Sage'] || 0);
            document.getElementById('advanced-count').textContent = advanced;
        }

        function changePage(page) {
            currentPage = page;
            loadData();
        }

        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Tambah Data Training';
            document.getElementById('dataForm').reset();
            document.getElementById('data-id').value = '';
            document.getElementById('dataModal').classList.remove('hidden');
        }

        async function editData(id) {
            try {
                const response = await fetch(`${API_BASE}/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    const data = result.data;
                    document.getElementById('modal-title').textContent = 'Edit Data Training';
                    document.getElementById('data-id').value = data.id;
                    document.getElementById('pendapatan').value = data.pendapatan;
                    document.getElementById('anggaran').value = data.anggaran;
                    document.getElementById('tabungan_dan_dana_darurat').value = data.tabungan_dan_dana_darurat;
                    document.getElementById('utang').value = data.utang;
                    document.getElementById('investasi').value = data.investasi;
                    document.getElementById('asuransi_dan_proteksi').value = data.asuransi_dan_proteksi;
                    document.getElementById('tujuan_jangka_panjang').value = data.tujuan_jangka_panjang;
                    document.getElementById('cluster').value = data.cluster;
                    document.getElementById('is_active').value = data.is_active ? '1' : '0';
                    document.getElementById('notes').value = data.notes || '';
                    
                    document.getElementById('dataModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Gagal memuat data');
            }
        }

        async function deleteData(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;
            
            try {
                const response = await fetch(`${API_BASE}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Data berhasil dihapus');
                    loadData();
                } else {
                    alert(result.message || 'Gagal menghapus data');
                }
            } catch (error) {
                console.error('Error deleting data:', error);
                alert('Gagal menghapus data');
            }
        }

        function closeModal() {
            document.getElementById('dataModal').classList.add('hidden');
        }

        document.getElementById('dataForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('data-id').value;
            const formData = {
                pendapatan: document.getElementById('pendapatan').value,
                anggaran: document.getElementById('anggaran').value,
                tabungan_dan_dana_darurat: document.getElementById('tabungan_dan_dana_darurat').value,
                utang: document.getElementById('utang').value,
                investasi: document.getElementById('investasi').value,
                asuransi_dan_proteksi: document.getElementById('asuransi_dan_proteksi').value,
                tujuan_jangka_panjang: document.getElementById('tujuan_jangka_panjang').value,
                cluster: document.getElementById('cluster').value,
                is_active: document.getElementById('is_active').value === '1',
                notes: document.getElementById('notes').value || null
            };
            
            try {
                const url = id ? `${API_BASE}/${id}` : API_BASE;
                const method = id ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    closeModal();
                    loadData();
                } else {
                    alert(result.message || 'Gagal menyimpan data');
                }
            } catch (error) {
                console.error('Error saving data:', error);
                alert('Gagal menyimpan data');
            }
        });

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
</body>
</html>
