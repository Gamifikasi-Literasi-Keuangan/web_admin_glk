<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertanyaan Profiling - Admin Panel</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Pertanyaan Profiling</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Kelola pertanyaan untuk sistem profiling dan fuzzy logic
                    </p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-question-circle text-2xl text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pertanyaan</dt>
                                        <dd class="text-2xl font-semibold text-gray-900" id="total-questions">0</dd>
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
                                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Aktif</dt>
                                        <dd class="text-2xl font-semibold text-gray-900" id="active-questions">0</dd>
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
                                        <i class="fas fa-star text-2xl text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Skor</dt>
                                        <dd class="text-2xl font-semibold text-gray-900" id="total-score">0</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters & Actions -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1 flex gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Cari pertanyaan..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <select id="filter-active" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <button onclick="showAddModal()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Tambah Pertanyaan
                        </button>
                    </div>
                </div>

                <!-- Questions Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Maks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aspek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="questions-table-body" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                                    <p>Memuat data...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span id="showing-from">0</span> - <span id="showing-to">0</span> dari <span id="showing-total">0</span> pertanyaan
                    </div>
                    <div id="pagination-container" class="flex gap-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="question-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-xl font-bold text-gray-900">Tambah Pertanyaan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="question-form" class="space-y-6">
                <input type="hidden" id="question-id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pertanyaan *</label>
                        <input type="text" id="question-code" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Q1, Q2, dll">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skor Maksimal *</label>
                        <input type="number" id="max-score" required min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="40">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teks Pertanyaan *</label>
                    <textarea id="question-text" required rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan pertanyaan..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aspek Keuangan *</label>
                    <div id="aspects-checkboxes" class="grid grid-cols-2 gap-3 p-4 border border-gray-300 rounded-lg"></div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Opsi Jawaban * (Min. 2)</label>
                        <button type="button" onclick="addOption()" 
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-plus"></i> Tambah Opsi
                        </button>
                    </div>
                    <div id="options-container" class="space-y-3"></div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is-active" checked
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is-active" class="ml-2 block text-sm text-gray-700">Aktifkan pertanyaan</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeModal()" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_BASE_URL = '/api/admin/profiling-questions';
        const TOKEN = localStorage.getItem('admin_token');
        let currentPage = 1;
        let aspectsList = [];
        let optionCounter = 0;

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            if (!TOKEN) {
                window.location.href = '/login';
                return;
            }
            loadAspects();
            loadQuestions();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('search-input').addEventListener('input', debounce(() => {
                currentPage = 1;
                loadQuestions();
            }, 500));

            document.getElementById('filter-active').addEventListener('change', () => {
                currentPage = 1;
                loadQuestions();
            });

            document.getElementById('question-form').addEventListener('submit', handleSubmit);
        }

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

        async function loadAspects() {
            try {
                const response = await fetch(`${API_BASE_URL}/aspects`, {
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                aspectsList = data.data;
            } catch (error) {
                console.error('Error loading aspects:', error);
            }
        }

        async function loadQuestions() {
            try {
                const search = document.getElementById('search-input').value;
                const isActive = document.getElementById('filter-active').value;
                
                let url = `${API_BASE_URL}?page=${currentPage}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;
                if (isActive !== '') url += `&is_active=${isActive}`;

                const response = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                updateStatistics(data);
                renderTable(data.data.data);
                renderPagination(data.data);
            } catch (error) {
                console.error('Error loading questions:', error);
                showError('Gagal memuat data');
            }
        }

        function updateStatistics(data) {
            document.getElementById('total-questions').textContent = data.data.total;
            document.getElementById('active-questions').textContent = data.data.data.filter(q => q.is_active).length;
            
            const totalScore = data.data.data
                .filter(q => q.is_active)
                .reduce((sum, q) => sum + q.max_score, 0);
            document.getElementById('total-score').textContent = totalScore;
        }

        function renderTable(questions) {
            const tbody = document.getElementById('questions-table-body');
            
            if (questions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = questions.map(q => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${q.question_code}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">${q.question_text}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${q.max_score}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <div class="flex flex-wrap gap-1">
                            ${(q.aspects || []).slice(0, 3).map(a => `
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">${a.display_name || a.aspect_key}</span>
                            `).join('')}
                            ${(q.aspects || []).length > 3 ? `<span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">+${(q.aspects || []).length - 3}</span>` : ''}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="toggleActive(${q.id}, ${q.is_active})" 
                            class="px-3 py-1 rounded-full text-xs font-semibold ${q.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${q.is_active ? 'Aktif' : 'Tidak Aktif'}
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="viewQuestion(${q.id})" class="text-blue-600 hover:text-blue-800 mr-3" title="Lihat">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editQuestion(${q.id})" class="text-yellow-600 hover:text-yellow-800 mr-3" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteQuestion(${q.id})" class="text-red-600 hover:text-red-800" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination-container');
            const { current_page, last_page, from, to, total } = data;

            document.getElementById('showing-from').textContent = from || 0;
            document.getElementById('showing-to').textContent = to || 0;
            document.getElementById('showing-total').textContent = total;

            if (last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            
            // Previous button
            html += `
                <button onclick="changePage(${current_page - 1})" 
                    ${current_page === 1 ? 'disabled' : ''}
                    class="px-3 py-1 rounded ${current_page === 1 ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700 hover:bg-gray-50'} border">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Page numbers
            for (let i = 1; i <= last_page; i++) {
                if (i === 1 || i === last_page || (i >= current_page - 1 && i <= current_page + 1)) {
                    html += `
                        <button onclick="changePage(${i})" 
                            class="px-3 py-1 rounded ${i === current_page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} border">
                            ${i}
                        </button>
                    `;
                } else if (i === current_page - 2 || i === current_page + 2) {
                    html += '<span class="px-2">...</span>';
                }
            }

            // Next button
            html += `
                <button onclick="changePage(${current_page + 1})" 
                    ${current_page === last_page ? 'disabled' : ''}
                    class="px-3 py-1 rounded ${current_page === last_page ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700 hover:bg-gray-50'} border">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            container.innerHTML = html;
        }

        function changePage(page) {
            currentPage = page;
            loadQuestions();
        }

        function showAddModal() {
            document.getElementById('modal-title').textContent = 'Tambah Pertanyaan';
            document.getElementById('question-form').reset();
            document.getElementById('question-id').value = '';
            optionCounter = 0;
            renderAspectsCheckboxes();
            renderOptions([]);
            document.getElementById('question-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('question-modal').classList.add('hidden');
        }

        function renderAspectsCheckboxes(selectedAspects = []) {
            const container = document.getElementById('aspects-checkboxes');
            container.innerHTML = aspectsList.map(aspect => `
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="aspects" value="${aspect.id}" 
                        ${selectedAspects.includes(aspect.id) ? 'checked' : ''}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="text-sm text-gray-700">${aspect.display_name}</span>
                </label>
            `).join('');
        }

        function renderOptions(options = []) {
            const container = document.getElementById('options-container');
            
            if (options.length === 0) {
                options = [
                    { option_text: '', score: '' },
                    { option_text: '', score: '' }
                ];
            }

            container.innerHTML = options.map((opt, index) => `
                <div class="flex gap-3 items-start" data-option-index="${index}">
                    <input type="hidden" name="options[${index}][id]" value="${opt.id || ''}">
                    <div class="flex-1">
                        <input type="text" name="options[${index}][option_text]" 
                            value="${opt.option_text || ''}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Teks opsi">
                    </div>
                    <div class="w-32">
                        <input type="number" name="options[${index}][score]" 
                            value="${opt.score ?? ''}" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Skor">
                    </div>
                    <button type="button" onclick="removeOption(this)" 
                        class="px-3 py-2 text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `).join('');

            optionCounter = options.length;
        }

        function addOption() {
            const container = document.getElementById('options-container');
            const newOption = `
                <div class="flex gap-3 items-start" data-option-index="${optionCounter}">
                    <div class="flex-1">
                        <input type="text" name="options[${optionCounter}][option_text]" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Teks opsi">
                    </div>
                    <div class="w-32">
                        <input type="number" name="options[${optionCounter}][score]" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Skor">
                    </div>
                    <button type="button" onclick="removeOption(this)" 
                        class="px-3 py-2 text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newOption);
            optionCounter++;
        }

        function removeOption(button) {
            const container = document.getElementById('options-container');
            if (container.children.length > 2) {
                button.closest('[data-option-index]').remove();
            } else {
                alert('Minimal harus ada 2 opsi jawaban');
            }
        }

        async function handleSubmit(e) {
            e.preventDefault();
            
            const questionId = document.getElementById('question-id').value;
            const formData = {
                question_code: document.getElementById('question-code').value,
                question_text: document.getElementById('question-text').value,
                max_score: parseInt(document.getElementById('max-score').value),
                is_active: document.getElementById('is-active').checked ? 1 : 0,
                aspect_ids: Array.from(document.querySelectorAll('input[name="aspects"]:checked')).map(cb => parseInt(cb.value)),
                options: []
            };

            // Collect options
            const optionDivs = document.querySelectorAll('[data-option-index]');
            optionDivs.forEach((div, index) => {
                const optionId = div.querySelector(`input[name="options[${div.dataset.optionIndex}][id]"]`)?.value;
                const optionText = div.querySelector(`input[name="options[${div.dataset.optionIndex}][option_text]"]`).value;
                const score = div.querySelector(`input[name="options[${div.dataset.optionIndex}][score]"]`).value;
                
                const option = {
                    option_code: String.fromCharCode(65 + index), // A, B, C, D, E...
                    option_text: optionText,
                    score: parseInt(score)
                };
                
                if (optionId) option.id = parseInt(optionId);
                
                formData.options.push(option);
            });

            try {
                const url = questionId ? `${API_BASE_URL}/${questionId}` : API_BASE_URL;
                const method = questionId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    showSuccess(data.message);
                    closeModal();
                    loadQuestions();
                } else {
                    showError(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal menyimpan data');
            }
        }

        async function viewQuestion(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    const q = data.data;
                    alert(`
Kode: ${q.question_code}
Pertanyaan: ${q.question_text}
Skor Maksimal: ${q.max_score}
Status: ${q.is_active ? 'Aktif' : 'Tidak Aktif'}

Opsi Jawaban:
${q.options.map(o => `- ${o.option_text} (Skor: ${o.score})`).join('\n')}

Aspek Keuangan:
${q.aspects.map(a => `- ${a.display_name}`).join('\n')}
                    `);
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal memuat detail');
            }
        }

        async function editQuestion(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    const q = data.data;
                    
                    document.getElementById('modal-title').textContent = 'Edit Pertanyaan';
                    document.getElementById('question-id').value = q.id;
                    document.getElementById('question-code').value = q.question_code;
                    document.getElementById('question-text').value = q.question_text;
                    document.getElementById('max-score').value = q.max_score;
                    document.getElementById('is-active').checked = q.is_active;
                    
                    const selectedAspects = q.aspects.map(a => a.id);
                    renderAspectsCheckboxes(selectedAspects);
                    renderOptions(q.options);
                    
                    document.getElementById('question-modal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal memuat data');
            }
        }

        async function deleteQuestion(id) {
            if (!confirm('Yakin ingin menghapus pertanyaan ini?')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showSuccess(data.message);
                    loadQuestions();
                } else {
                    showError(data.message || 'Gagal menghapus');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal menghapus data');
            }
        }

        async function toggleActive(id, currentStatus) {
            try {
                const response = await fetch(`${API_BASE_URL}/${id}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showSuccess(data.message);
                    loadQuestions();
                } else {
                    showError(data.message || 'Gagal mengubah status');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Gagal mengubah status');
            }
        }

        function showSuccess(message) {
            alert('✓ ' + message);
        }

        function showError(message) {
            alert('✗ ' + message);
        }
    </script>
</body>
</html>
