// Global State
let currentTab = 'scenarios';
let currentPage = 1;
let lastPaginationData = null;
const headers = {
    'Authorization': `Bearer ${token}`, // token dari layout
    'Accept': 'application/json'
};

// Sub-kategori mapping sesuai Excel
const subKategoriOptions = {
    'Pendapatan': ['Uang bulanan', 'Freelance', 'Beasiswa'],
    'Anggaran': ['Makan', 'Transport', 'Nongkrong'],
    'Tabungan & Dana Darurat': ['Tabungan', 'Dana Darurat', 'Deposito'],
    'Utang': ['Pinjaman Teman', 'Pinjol', 'Paylater'],
    'Investasi': ['Reksadana', 'Saham', 'Cryptocurrency'],
    'Asuransi': ['Asuransi Kesehatan', 'Asuransi Kendaraan', 'Asuransi Barang/Harta'],
    'Tujuan Jangka Panjang': ['Pendidikan', 'Pengalaman', 'Aset Produktif']
};

// Function to update sub-kategori dropdown based on aspek selection
// Function to update sub-kategori dropdown based on aspek selection
function updateSubKategori() {
    const aspekSelect = document.getElementById('aspek-select');
    const kategoriList = document.getElementById('kategori-list'); // Target datalist
    const kategoriInput = document.getElementById('kategori-input'); // Input field (optional ref)

    if (!aspekSelect || !kategoriList) return;

    const aspek = aspekSelect.value;
    const subs = subKategoriOptions[aspek] || [];

    // Clear input if aspek changes? Better keep it or clear it. Let's keep UX simple.
    // Populate datalist
    kategoriList.innerHTML = subs.map(s => `<option value="${s}">`).join('');
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    loadData();
});

// --- TAB SWITCHING ---
function switchTab(tab) {
    currentTab = tab;
    currentPage = 1; // Reset ke halaman 1 saat ganti tab

    // Reset semua tab ke state inactive
    document.querySelectorAll('.tab-btn').forEach(btn => {
        // Reset ke state inactive: background putih, text zinc, border zinc
        btn.className = 'tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300 whitespace-nowrap';
    });

    // Set tab aktif dengan style hijau
    const activeTab = document.getElementById(`tab-${tab}`);
    if (activeTab) {
        activeTab.className = 'tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-green-500 text-white shadow-md whitespace-nowrap';
    }

    // Reset Search & Load Data
    document.getElementById('searchInput').value = '';
    loadData();
}

// --- DATA LOADING ---
async function loadData(keyword = '', page = 1) {
    currentPage = page;
    const wrapper = document.getElementById('table-wrapper');
    wrapper.innerHTML = `
        <div class="flex items-center justify-center py-16">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mb-3"></div>
                <p class="text-zinc-500">Memuat data konten...</p>
            </div>
        </div>
    `;

    try {
        let url;
        if (currentTab === 'scenarios') url = `${BASE_API}/scenarios?limit=10&page=${page}&search=${keyword}`;
        else if (currentTab === 'risk') url = `${BASE_API}/cards/risk?limit=10&page=${page}&search=${keyword}`;
        else if (currentTab === 'chance') url = `${BASE_API}/cards/chance?limit=10&page=${page}&search=${keyword}`;
        else if (currentTab === 'quiz') url = `${BASE_API}/cards/quiz?limit=10&page=${page}&search=${keyword}`;

        const response = await fetch(url, { headers });
        if (!response.ok) throw new Error("Gagal mengambil data");
        const json = await response.json();

        // Simpan data pagination
        lastPaginationData = {
            current_page: json.current_page,
            last_page: json.last_page,
            total: json.total,
            from: json.from,
            to: json.to
        };

        renderTable(json.data || []);
        renderPagination();

    } catch (e) {
        wrapper.innerHTML = `
            <div class="text-center py-16">
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 max-w-md mx-auto">
                    <div class="bg-red-100 p-3 rounded-full w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                        <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <p class="text-red-600 font-medium">Error: ${e.message}</p>
                </div>
            </div>
        `;
    }
}

// --- PAGINATION ---
function renderPagination() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper || !lastPaginationData) return;

    const { current_page, last_page, total, from, to } = lastPaginationData;

    if (last_page <= 1) {
        paginationWrapper.innerHTML = `<p class="text-zinc-500 text-sm">Menampilkan ${total} data</p>`;
        return;
    }

    let pagesHtml = '';
    const maxVisible = 5;
    let startPage = Math.max(1, current_page - Math.floor(maxVisible / 2));
    let endPage = Math.min(last_page, startPage + maxVisible - 1);

    if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }

    // Previous button
    pagesHtml += `<button onclick="goToPage(${current_page - 1})" ${current_page === 1 ? 'disabled' : ''} 
        class="px-3 py-1 rounded border ${current_page === 1 ? 'bg-zinc-100 text-zinc-400 cursor-not-allowed' : 'bg-white text-zinc-700 hover:bg-zinc-50'}">
        <i class="fas fa-chevron-left"></i>
    </button>`;

    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        pagesHtml += `<button onclick="goToPage(${i})" 
            class="px-3 py-1 rounded border ${i === current_page ? 'bg-green-500 text-white' : 'bg-white text-zinc-700 hover:bg-zinc-50'}">
            ${i}
        </button>`;
    }

    // Next button
    pagesHtml += `<button onclick="goToPage(${current_page + 1})" ${current_page === last_page ? 'disabled' : ''} 
        class="px-3 py-1 rounded border ${current_page === last_page ? 'bg-zinc-100 text-zinc-400 cursor-not-allowed' : 'bg-white text-zinc-700 hover:bg-zinc-50'}">
        <i class="fas fa-chevron-right"></i>
    </button>`;

    paginationWrapper.innerHTML = `
        <div class="flex items-center justify-between">
            <p class="text-zinc-500 text-sm">Menampilkan ${from}-${to} dari ${total} data</p>
            <div class="flex gap-1">${pagesHtml}</div>
        </div>
    `;
}

function goToPage(page) {
    if (!lastPaginationData) return;
    if (page < 1 || page > lastPaginationData.last_page) return;
    const keyword = document.getElementById('searchInput')?.value || '';
    loadData(keyword, page);
}

// --- RENDER TABLE ---
function renderTable(data) {
    const wrapper = document.getElementById('table-wrapper');

    if (data.length === 0) {
        wrapper.innerHTML = `
            <div class="text-center py-16">
                <div class="bg-zinc-100 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fa-solid fa-inbox text-zinc-400 text-xl"></i>
                </div>
                <p class="text-zinc-500 font-medium">Data tidak ditemukan</p>
                <p class="text-zinc-400 text-sm mt-1">Coba gunakan kata kunci pencarian yang berbeda</p>
            </div>
        `;
        return;
    }

    let columns = [];
    let rows = '';

    // Tentukan Kolom berdasarkan Tab
    if (currentTab === 'scenarios') {
        columns = ['Skenario', 'Sub Kategori', 'Opsi', 'Aksi'];
        data.forEach(item => {
            rows += `
                <tr class="hover:bg-green-50 border-b border-zinc-100 transition-colors">
                    <td class="px-6 py-4 font-semibold text-zinc-800 max-w-xs truncate" title="${item.title}">${item.title}</td>
                    <td class="px-6 py-4"><span class="bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full border border-indigo-200 font-medium">${item.category}</span></td>
                    <td class="px-6 py-4 text-zinc-600 font-medium">${item.options_count} Opsi</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="showDetail('${item.id}')" class="bg-black/70 hover:bg-black text-white p-2 rounded-lg transition-colors" title="Lihat Detail">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button onclick="editItem('${item.id}')" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 hover:text-yellow-800 p-2 rounded-lg transition-colors border border-yellow-200 hover:border-yellow-300" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="deleteItem('${item.id}')" class="bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 p-2 rounded-lg transition-colors border border-red-200 hover:border-red-300" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    } else if (currentTab === 'quiz') {
        columns = ['Pertanyaan', 'Akurasi', 'Total Dimainkan', 'Aksi'];
        data.forEach(item => {
            rows += `
                <tr class="hover:bg-green-50 border-b border-zinc-100 transition-colors">
                    <td class="px-6 py-4 text-zinc-800 max-w-md">
                        <div class="truncate font-medium">${item.question || '-'}</div>
                    </td>
                    <td class="px-6 py-4"><span class="text-green-600 font-bold">${item.accuracy || '-'}</span></td>
                    <td class="px-6 py-4 text-zinc-600 font-medium">${item.total_attempts || 0}x</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="showDetail('${item.id}')" class="bg-black/70 hover:bg-black text-white p-2 rounded-lg transition-colors" title="Lihat Detail">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button onclick="editItem('${item.id}')" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 hover:text-yellow-800 p-2 rounded-lg transition-colors border border-yellow-200 hover:border-yellow-300" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="deleteItem('${item.id}')" class="bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 p-2 rounded-lg transition-colors border border-red-200 hover:border-red-300" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    } else { // Risk & Chance
        columns = ['Judul', 'Efek', 'Kesulitan', 'Penggunaan', 'Aksi'];
        data.forEach(item => {
            const effect = currentTab === 'risk' ? item.impact : item.benefit;
            const color = currentTab === 'risk' ? 'text-red-600' : 'text-green-600';
            rows += `
                <tr class="hover:bg-green-50 border-b border-zinc-100 transition-colors">
                    <td class="px-6 py-4 font-semibold text-zinc-800">${item.title}</td>
                    <td class="px-6 py-4 ${color} font-bold">${effect > 0 ? '+' + effect : effect}</td>
                    <td class="px-6 py-4">${renderDifficulty(item.difficulty)}</td>
                    <td class="px-6 py-4 text-zinc-600 font-medium">${item.usage}x</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="showDetail('${item.id}')" class="bg-black/70 hover:bg-black text-white p-2 rounded-lg transition-colors" title="Lihat Detail">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button onclick="editItem('${item.id}')" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 hover:text-yellow-800 p-2 rounded-lg transition-colors border border-yellow-200 hover:border-yellow-300" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="deleteItem('${item.id}')" class="bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 p-2 rounded-lg transition-colors border border-red-200 hover:border-red-300" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    let headerHtml = columns.map(c => `<th class="px-6 py-4 bg-zinc-50 text-left text-xs font-bold text-zinc-600 uppercase tracking-wide border-b border-zinc-200">${c}</th>`).join('');

    wrapper.innerHTML = `
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead><tr>${headerHtml}</tr></thead>
                <tbody class="bg-white">${rows}</tbody>
            </table>
        </div>
    `;
}

// --- UTILS ---
function renderDifficulty(level) {
    if (level === 1) return '<span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full border border-green-200 font-semibold">Sangat Mudah</span>';
    if (level === 2) return '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full border border-blue-200 font-semibold">Mudah</span>';
    if (level === 3) return '<span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full border border-yellow-200 font-semibold">Sedang</span>';
    if (level === 4) return '<span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full border border-orange-200 font-semibold">Sulit</span>';
    return '<span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full border border-red-200 font-semibold">Sangat Sulit</span>';
}

// Score ranges berdasarkan Bobot
const scoreRanges = {
    1: { min: 6, max: 15, default: 10 },    // Sangat Mudah
    2: { min: 21, max: 35, default: 28 },   // Mudah
    3: { min: 41, max: 60, default: 50 },   // Sedang
    4: { min: 67, max: 85, default: 76 },   // Sulit
    5: { min: 88, max: 100, default: 94 }   // Sangat Sulit
};

function updateScoreRange() {
    const bobotSelect = document.getElementById('bobot-select');
    const scoreInput = document.getElementById('score-input');
    const scoreRangeLabel = document.getElementById('score-range-label');

    if (!bobotSelect || !scoreInput) return;

    const bobot = parseInt(bobotSelect.value);
    const range = scoreRanges[bobot] || scoreRanges[1];

    // Update label
    if (scoreRangeLabel) {
        scoreRangeLabel.textContent = `(${range.min}-${range.max})`;
    }

    // Update input constraints
    scoreInput.min = range.min;
    scoreInput.max = range.max;

    // Set default value if current value is out of range
    const currentValue = parseInt(scoreInput.value) || 0;
    if (currentValue < range.min || currentValue > range.max) {
        scoreInput.value = range.default;
    }
}

let timeout;
function handleSearch(val) {
    clearTimeout(timeout);
    timeout = setTimeout(() => loadData(val), 500);
}

// --- MODAL DETAIL (PERBAIKAN TAMPILAN) ---
async function showDetail(id) {
    currentId = id; // Simpan ID untuk edit/delete
    const modal = document.getElementById('detail-modal');
    const body = document.getElementById('modal-body');
    const title = document.getElementById('modal-title');
    const editBtn = document.getElementById('editBtn');
    const deleteBtn = document.getElementById('deleteBtn');

    modal.classList.remove('hidden');
    body.innerHTML = '<div class="loader"></div>';

    // Tampilkan tombol Edit & Delete
    editBtn.classList.remove('hidden');
    deleteBtn.classList.remove('hidden');

    try {
        let url;
        if (currentTab === 'scenarios') url = `${BASE_API}/scenarios/${id}`;
        else if (currentTab === 'quiz') url = `${BASE_API}/cards/quiz/${id}`;
        else url = `${BASE_API}/cards/${currentTab}/${id}`;

        const res = await fetch(url, { headers });
        const json = await res.json();
        const item = json.data || json;

        // 1. DETAIL SKENARIO
        if (currentTab === 'scenarios') {
            title.innerText = 'Detail Skenario';

            // Helper function untuk render difficulty badge
            const getDifficultyBadge = (diff) => {
                if (diff == 1) return '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Mudah</span>';
                if (diff == 2) return '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">Sedang</span>';
                return '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Sulit</span>';
            };

            body.innerHTML = `
                <div class="bg-indigo-50 p-4 rounded border border-indigo-200 mb-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-1">${item.content.title || 'Skenario'}</h3>
                            <span class="bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full font-medium">Kategori: ${item.content.category}</span>
                        </div>
                        <div class="flex gap-2">
                            ${getDifficultyBadge(item.content.difficulty || 1)}
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Skor: ${item.content.score || 5}</span>
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed text-lg">${item.content.question}</p>
                </div>
                <h5 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wide">Opsi Jawaban:</h5>
                <div class="space-y-3">
                    ${item.options.map(opt => `
                        <div class="p-4 border rounded-lg ${opt.is_correct ? 'bg-green-50 border-green-300' : 'bg-white border-gray-200'}">
                            <div class="flex items-start gap-3 mb-2">
                                <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full ${opt.is_correct ? 'bg-green-600' : 'bg-indigo-600'} text-white font-bold text-sm">${opt.label}</span>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-gray-800 font-medium">${opt.text}</p>
                                        ${opt.is_correct ? '<span class="bg-green-600 text-white text-xs px-2 py-0.5 rounded font-medium">✓ Jawaban Benar</span>' : ''}
                                    </div>
                                    <span class="inline-block text-sm font-bold ${opt.impact >= 0 ? 'text-green-600' : 'text-red-600'}">
                                        Total Skor: ${opt.impact >= 0 ? '+' : ''}${opt.impact || 0}
                                    </span>
                                </div>
                            </div>
                            ${opt.feedback ? `
                                <div class="mt-2 p-2 bg-blue-50 border border-blue-100 rounded text-sm">
                                    <span class="text-blue-600 font-semibold">Respons AI:</span>
                                    <p class="text-blue-800">${opt.feedback}</p>
                                </div>
                            ` : ''}
                        </div>
                    `).join('')}
                </div>
            `;

            // 2. DETAIL QUIZ (KUIS)
        } else if (currentTab === 'quiz') {
            title.innerText = 'Detail Kuis';
            body.innerHTML = `
                <div class="bg-yellow-50 p-4 rounded border border-yellow-200 mb-4">
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Pertanyaan:</h4>
                    <p class="text-gray-800 text-lg">${item.question}</p>
                </div>
                
                <div class="flex gap-4 mb-4 text-sm text-gray-600">
                    <div class="bg-white px-3 py-1 rounded border">Kesulitan: <strong>${item.difficulty} - ${item.difficulty == 1 ? 'Mudah' : (item.difficulty == 2 ? 'Sedang' : 'Sulit')}</strong></div>
                    <div class="bg-white px-3 py-1 rounded border">Score Benar: <span class="text-green-600 font-bold">+${item.correct_score}</span></div>
                    <div class="bg-white px-3 py-1 rounded border">Score Salah: <span class="text-red-600 font-bold">${item.incorrect_score}</span></div>
                </div>

                <h5 class="font-bold text-gray-700 mb-2 text-sm uppercase tracking-wide">Pilihan Jawaban:</h5>
                <ul class="space-y-2">
                    ${item.options.map(opt => `
                        <li class="flex items-center gap-3 p-3 border rounded ${opt.label === item.correct_option_id ? 'bg-green-100 border-green-300' : 'bg-white'}">
                            <span class="font-bold text-gray-500">${opt.label}.</span>
                            <span class="flex-1 text-gray-800">${opt.text}</span>
                            ${opt.label === item.correct_option_id ? '<span class="text-xs bg-green-600 text-white px-2 py-1 rounded">Jawaban Benar</span>' : ''}
                        </li>
                    `).join('')}
                </ul>

                ${item.learning_objective ? `
                <div class="mt-4 bg-blue-50 p-4 rounded border border-blue-200">
                    <p class="text-xs text-blue-500 uppercase tracking-wider mb-2">Learning Objective</p>
                    <p class="text-blue-800">${item.learning_objective}</p>
                </div>
                ` : ''}

                ${item.tags ? `
                <div class="mt-4 flex flex-wrap gap-2">
                    ${item.tags.split(',').map(tag => `<span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">${tag.trim()}</span>`).join('')}
                </div>
                ` : ''}
            `;

            // 3. DETAIL RISK / CHANCE
        } else {
            const isRisk = currentTab === 'risk';
            const impactVal = isRisk ? item.impact : item.benefit;
            const impactColor = isRisk ? 'text-red-600' : 'text-green-600';
            const bgHeader = isRisk ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200';
            const badgeColor = isRisk ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700';

            // Helper function untuk render difficulty badge
            const getDifficultyBadge = (diff) => {
                if (diff == 1) return '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Mudah</span>';
                if (diff == 2) return '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">Sedang</span>';
                return '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Sulit</span>';
            };

            title.innerText = isRisk ? 'Kartu Risiko' : 'Kartu Kesempatan';
            body.innerHTML = `
                <div class="${bgHeader} p-5 rounded-lg border mb-4">
                    <div class="flex justify-between items-start mb-3">
                        <h2 class="text-2xl font-bold text-gray-800">${item.title}</h2>
                        <div class="flex gap-2">
                            ${getDifficultyBadge(item.difficulty || 1)}
                            <span class="${badgeColor} px-2 py-1 rounded text-xs font-medium">${item.categories || 'Umum'}</span>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"${item.description}"</p>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="bg-white p-4 rounded shadow-sm border border-gray-100 text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Efek Score</p>
                        <span class="text-3xl font-bold ${impactColor}">${impactVal > 0 ? '+' + impactVal : impactVal}</span>
                    </div>
                    <div class="bg-white p-4 rounded shadow-sm border border-gray-100 text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Target Tile</p>
                        <span class="text-xl font-bold text-purple-600">${item.target_tile || '-'}</span>
                    </div>
                    <div class="bg-white p-4 rounded shadow-sm border border-gray-100 text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Statistik</p>
                        <span class="text-xl font-bold text-gray-800">${item.stats?.landed_count || 0}</span>
                        <span class="text-xs text-gray-500 block">Kali Muncul</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-white p-4 rounded border border-gray-200">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Aksi/Efek</p>
                        <p class="text-gray-800 font-medium">${item.action_type || 'default'}</p>
                    </div>
                    <div class="bg-white p-4 rounded border border-gray-200">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Expected Benefit</p>
                        <p class="text-gray-800 font-medium">${item.expected_benefit || 5}</p>
                    </div>
                </div>
                
                ${item.learning_objective ? `
                <div class="bg-blue-50 p-4 rounded border border-blue-200 mb-4">
                    <p class="text-xs text-blue-500 uppercase tracking-wider mb-2">Learning Objective</p>
                    <p class="text-blue-800">${item.learning_objective}</p>
                </div>
                ` : ''}
                
                ${item.tags ? `
                <div class="flex flex-wrap gap-2">
                    ${item.tags.split(',').map(tag => `<span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">${tag.trim()}</span>`).join('')}
                </div>
                ` : ''}
            `;
        }

    } catch (e) {
        body.innerHTML = `<p class="text-red-500 bg-red-100 p-4 rounded">Gagal memuat detail: ${e.message}</p>`;
    }
}

function closeModal() {
    document.getElementById('detail-modal').classList.add('hidden');
}

// --- CRUD OPERATIONS ---
let currentId = null;
let isEditMode = false;

function openCreateModal() {
    isEditMode = false;
    currentId = null;
    document.getElementById('form-modal-title').innerText = `Tambah ${getContentTypeName()}`;
    renderForm();
    document.getElementById('form-modal').classList.remove('hidden');
}

function openEditModal() {
    isEditMode = true;
    document.getElementById('form-modal-title').innerText = `Edit ${getContentTypeName()}`;
    closeModal();
    renderForm(true);
    document.getElementById('form-modal').classList.remove('hidden');
}

function closeFormModal() {
    document.getElementById('form-modal').classList.add('hidden');
    document.getElementById('contentForm').reset();
}

function getContentTypeName() {
    if (currentTab === 'scenarios') return 'Skenario';
    if (currentTab === 'risk') return 'Kartu Risiko';
    if (currentTab === 'chance') return 'Kartu Kesempatan';
    if (currentTab === 'quiz') return 'Kuis';
}

async function renderForm(loadData = false) {
    const formBody = document.getElementById('form-body');
    let formHtml = '';
    let data = null;

    if (loadData && currentId) {
        try {
            let url;
            if (currentTab === 'scenarios') url = `${BASE_API}/scenarios/${currentId}`;
            else if (currentTab === 'quiz') url = `${BASE_API}/cards/quiz/${currentId}`;
            else url = `${BASE_API}/cards/${currentTab}/${currentId}`;

            const res = await fetch(url, { headers });
            const json = await res.json();
            data = json.data || json;
        } catch (e) {
            alert('Gagal memuat data: ' + e.message);
            return;
        }
    }

    if (currentTab === 'scenarios') {
        // Form untuk Skenario - sesuai struktur Excel
        const kategoriValue = data?.content?.category || '';

        // Sub-kategori mapping sesuai Excel
        const subKategoriOptions = {
            'Pendapatan': ['Uang bulanan', 'Freelance', 'Beasiswa'],
            'Anggaran': ['Makan', 'Transport', 'Nongkrong'],
            'Tabungan & Dana Darurat': ['Tabungan', 'Dana Darurat', 'Deposito'],
            'Utang': ['Pinjaman Teman', 'Pinjol', 'Paylater'],
            'Investasi': ['Reksadana', 'Saham', 'Cryptocurrency'],
            'Asuransi': ['Asuransi Kesehatan', 'Asuransi Kendaraan', 'Asuransi Barang/Harta'],
            'Tujuan Jangka Panjang': ['Pendidikan', 'Pengalaman', 'Aset Produktif']
        };

        // Detect aspek from current category
        let currentAspek = '';
        for (const [aspek, subs] of Object.entries(subKategoriOptions)) {
            if (subs.includes(kategoriValue) || aspek === kategoriValue) {
                currentAspek = aspek;
                break;
            }
        }

        formHtml = `
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Aspek Literasi</label>
                    <select id="aspek-select" name="aspek" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" onchange="updateSubKategori()">
                        <option value="">-- Pilih Aspek --</option>
                        <option value="Pendapatan" ${currentAspek === 'Pendapatan' ? 'selected' : ''}>Pendapatan</option>
                        <option value="Anggaran" ${currentAspek === 'Anggaran' ? 'selected' : ''}>Anggaran</option>
                        <option value="Tabungan & Dana Darurat" ${currentAspek === 'Tabungan & Dana Darurat' ? 'selected' : ''}>Tabungan & Dana Darurat</option>
                        <option value="Utang" ${currentAspek === 'Utang' ? 'selected' : ''}>Utang</option>
                        <option value="Investasi" ${currentAspek === 'Investasi' ? 'selected' : ''}>Investasi</option>
                        <option value="Asuransi" ${currentAspek === 'Asuransi' ? 'selected' : ''}>Asuransi</option>
                        <option value="Tujuan Jangka Panjang" ${currentAspek === 'Tujuan Jangka Panjang' ? 'selected' : ''}>Tujuan Jangka Panjang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kategori/Sub Aspek</label>
                    <input list="kategori-list" type="text" id="kategori-input" name="category" required 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" 
                        placeholder="Pilih atau ketik kategori..." autocomplete="off">
                    <datalist id="kategori-list"></datalist>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul Skenario</label>
                <input type="text" name="title" required placeholder="Contoh: Gaji Pertama"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" value="${data?.content?.title || ''}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Skenario/Pertanyaan</label>
                <textarea name="question" rows="3" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Contoh: Kamu mendapat gaji pertama Rp2.000.000. Apa yang kamu lakukan?">${data?.content?.question || ''}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="1" ${(data?.content?.difficulty || 1) == 1 ? 'selected' : ''}>1 - Mudah</option>
                        <option value="2" ${(data?.content?.difficulty) == 2 ? 'selected' : ''}>2 - Sedang</option>
                        <option value="3" ${(data?.content?.difficulty) == 3 ? 'selected' : ''}>3 - Sulit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Expected Benefit (Skor)</label>
                    <input type="number" name="expected_benefit" min="1" max="10" value="${data?.content?.score || 5}" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Opsi Jawaban (A, B, C)</label>
                <p class="text-xs text-gray-500 mb-3">Setiap opsi memiliki teks jawaban, respons AI, perubahan skor multi-aspek, dan tanda jawaban benar.</p>
                <div id="options-container" class="space-y-4">
                    ${data?.options && data.options.length > 0 ? data.options.slice(0, 3).map((opt, i) => `
                        <div class="border p-3 rounded-lg bg-gray-50">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-indigo-600 w-8 text-lg">${opt.label}.</span>
                                <input type="hidden" name="option_label_${i}" value="${opt.label}">
                                <input type="text" name="option_text_${i}" value="${opt.text || ''}" placeholder="Teks jawaban opsi ${opt.label}" required class="flex-1 px-2 py-1 border rounded">
                                <label class="flex items-center gap-1 text-sm">
                                    <input type="checkbox" name="option_correct_${i}" value="1" ${opt.is_correct ? 'checked' : ''} class="w-4 h-4 text-green-600">
                                    <span class="text-green-700 font-medium">Benar</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 1</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek1_${i}" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="pendapatan">Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score1_${i}" value="${opt.is_correct ? 5 : -3}" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 2 (Opsional)</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek2_${i}" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="">-- Tidak ada --</option>
                                            <option value="pendapatan">Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score2_${i}" value="0" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <textarea name="option_response_${i}" rows="2" placeholder="Respons AI untuk opsi ${opt.label}..." class="w-full px-2 py-1 border rounded text-sm">${opt.feedback || ''}</textarea>
                            </div>
                        </div>
                    `).join('') : `
                        <div class="border p-3 rounded-lg bg-gray-50">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-indigo-600 w-8 text-lg">A.</span>
                                <input type="hidden" name="option_label_0" value="A">
                                <input type="text" name="option_text_0" placeholder="Teks jawaban opsi A (Jawaban Benar)" required class="flex-1 px-2 py-1 border rounded">
                                <label class="flex items-center gap-1 text-sm">
                                    <input type="checkbox" name="option_correct_0" value="1" checked class="w-4 h-4 text-green-600">
                                    <span class="text-green-700 font-medium">Benar</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 1</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek1_0" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="pendapatan" selected>Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score1_0" value="5" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 2 (Opsional)</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek2_0" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="" selected>-- Tidak ada --</option>
                                            <option value="pendapatan">Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score2_0" value="3" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <textarea name="option_response_0" rows="2" placeholder="Respons AI: Bagus! Ini adalah keputusan bijak..." class="w-full px-2 py-1 border rounded text-sm"></textarea>
                            </div>
                        </div>
                        <div class="border p-3 rounded-lg bg-gray-50">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-indigo-600 w-8 text-lg">B.</span>
                                <input type="hidden" name="option_label_1" value="B">
                                <input type="text" name="option_text_1" placeholder="Teks jawaban opsi B" required class="flex-1 px-2 py-1 border rounded">
                                <label class="flex items-center gap-1 text-sm">
                                    <input type="checkbox" name="option_correct_1" value="1" class="w-4 h-4 text-green-600">
                                    <span class="text-green-700 font-medium">Benar</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 1</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek1_1" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="pendapatan" selected>Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score1_1" value="-3" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 2 (Opsional)</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek2_1" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="" selected>-- Tidak ada --</option>
                                            <option value="pendapatan">Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score2_1" value="-2" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <textarea name="option_response_1" rows="2" placeholder="Respons AI: Hati-hati, keputusan ini berisiko..." class="w-full px-2 py-1 border rounded text-sm"></textarea>
                            </div>
                        </div>
                        <div class="border p-3 rounded-lg bg-gray-50">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-indigo-600 w-8 text-lg">C.</span>
                                <input type="hidden" name="option_label_2" value="C">
                                <input type="text" name="option_text_2" placeholder="Teks jawaban opsi C" required class="flex-1 px-2 py-1 border rounded">
                                <label class="flex items-center gap-1 text-sm">
                                    <input type="checkbox" name="option_correct_2" value="1" class="w-4 h-4 text-green-600">
                                    <span class="text-green-700 font-medium">Benar</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 1</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek1_2" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="pendapatan" selected>Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score1_2" value="-1" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Aspek Skor 2 (Opsional)</label>
                                    <div class="flex gap-1">
                                        <select name="option_aspek2_2" class="flex-1 px-2 py-1 border rounded text-sm">
                                            <option value="" selected>-- Tidak ada --</option>
                                            <option value="pendapatan">Pendapatan</option>
                                            <option value="anggaran">Anggaran</option>
                                            <option value="tabungan_dan_dana_darurat">Tabungan & Dana Darurat</option>
                                            <option value="utang">Utang</option>
                                            <option value="investasi">Investasi</option>
                                            <option value="asuransi">Asuransi</option>
                                            <option value="literasi_keuangan">Literasi Keuangan</option>
                                        </select>
                                        <input type="number" name="option_score2_2" value="2" class="w-16 px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <textarea name="option_response_2" rows="2" placeholder="Respons AI: Pilihan cukup aman, tapi kurang optimal..." class="w-full px-2 py-1 border rounded text-sm"></textarea>
                            </div>
                        </div>
                    `}
                </div>
            </div>
        `;

        // Add script to populate sub-kategori after form renders
        setTimeout(() => {
            const aspekSelect = document.getElementById('aspek-select');
            const kategoriInput = document.getElementById('kategori-input');
            if (aspekSelect && kategoriInput) {
                aspekSelect.addEventListener('change', updateSubKategori);
                if (aspekSelect.value) updateSubKategori();
                // Pre-select current kategori
                if (kategoriValue) {
                    kategoriInput.value = kategoriValue;
                }
            }
        }, 100);
    } else if (currentTab === 'quiz') {
        const diffValue = data?.difficulty || 1;
        formHtml = `
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Pertanyaan</label>
                <textarea name="question" rows="3" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">${data?.question || ''}</textarea>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kesulitan</label>
                    <select name="difficulty" required class="w-full px-3 py-2 border rounded-lg">
                        <option value="1" ${diffValue == 1 ? 'selected' : ''}>1 - Mudah</option>
                        <option value="2" ${diffValue == 2 ? 'selected' : ''}>2 - Sedang</option>
                        <option value="3" ${diffValue == 3 ? 'selected' : ''}>3 - Sulit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Score Benar</label>
                    <input type="number" name="correctScore" value="${data?.correct_score || 10}" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Score Salah</label>
                    <input type="number" name="incorrectScore" value="${data?.incorrect_score || -5}" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tags</label>
                    <input type="text" name="tags" value="${data?.tags || ''}" placeholder="Contoh: Budgeting, Saving"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Learning Objective</label>
                    <textarea name="learning_objective" rows="1" placeholder="Contoh: Memahami fungsi dana darurat."
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">${data?.learning_objective || ''}</textarea>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Opsi Jawaban</label>
                <div id="quiz-options">
                    ${(data?.options || [
                { label: 'A', text: '' },
                { label: 'B', text: '' },
                { label: 'C', text: '' }
            ]).map((opt, i) => `
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="quiz_label_${i}" value="${opt.label}" readonly class="w-12 px-2 py-1 border rounded bg-gray-100 text-center font-bold">
                            <input type="text" name="quiz_text_${i}" value="${opt.text}" placeholder="Teks jawaban opsi ${opt.label}" required class="flex-1 px-2 py-1 border rounded">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="quiz_correct_${i}" ${opt.label === data?.correct_option_id ? 'checked' : ''} class="mr-1 w-4 h-4 text-green-600">
                                <span class="text-sm font-medium text-gray-700">Benar</span>
                            </label>
                        </div>
                    `).join('')}
                </div>
                <p class="text-xs text-gray-500 mt-1">Centang kotak "Benar" pada jawaban yang tepat.</p>
                </div>
            </div>
        `;
    } else { // Risk & Chance
        const diffValue = data?.difficulty || 1;
        const isRisk = currentTab === 'risk';

        // Fetch board tiles untuk dropdown target_tile
        let tilesOptions = '<option value="">-- Pilih Tile Tujuan --</option>';
        try {
            const tilesRes = await fetch(`${BASE_API}/tiles`, { headers });
            if (tilesRes.ok) {
                const tilesJson = await tilesRes.json();
                const tiles = tilesJson.data || tilesJson || [];
                tilesOptions += tiles.map(tile => {
                    const tilePosition = tile.tile_id ?? tile.position_index ?? 0;
                    const selected = data?.target_tile === tilePosition ? 'selected' : '';
                    return `<option value="${tilePosition}" ${selected}>${tilePosition} - ${tile.name}</option>`;
                }).join('');
            }
        } catch (e) {
            console.error('Gagal memuat tiles:', e);
        }

        formHtml = `
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul Kartu</label>
                <input type="text" name="title" value="${data?.title || ''}" required placeholder="Contoh: HP Rusak"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Narasi/Deskripsi</label>
                <textarea name="narration" rows="3" required placeholder="Contoh: HP mu rusak! Karena tidak ada dana darurat..."
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">${data?.description || ''}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Dampak Score</label>
                    <input type="number" name="scoreChange" value="${data?.impact || data?.benefit || (isRisk ? -5 : 5)}" required
                        class="w-full px-3 py-2 border rounded-lg" placeholder="${isRisk ? 'Contoh: -5' : 'Contoh: +5'}">
                    <p class="text-xs text-gray-500 mt-1">${isRisk ? 'Nilai negatif untuk risiko' : 'Nilai positif untuk kesempatan'}</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Aksi/Efek</label>
                    <input type="text" id="action-input" name="action" value="${data?.action_type || ''}" placeholder="Contoh: Pindah ke Kotak Dana Darurat"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Deskripsi efek yang terjadi (opsional)</p>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Target Tile (Tujuan Pindah) <span class="text-red-500">*</span></label>
                <select id="target-tile-select" name="target_tile" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                    ${tilesOptions}
                </select>
                <p class="text-xs text-gray-500 mt-1">Tile tujuan ketika kartu ini diambil (wajib diisi)</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Tingkat Kesulitan</label>
                <select name="difficulty" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                    <option value="1" ${diffValue == 1 ? 'selected' : ''}>1 - Mudah</option>
                    <option value="2" ${diffValue == 2 ? 'selected' : ''}>2 - Sedang</option>
                    <option value="3" ${diffValue == 3 ? 'selected' : ''}>3 - Sulit</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tags</label>
                    <input type="text" name="tags" value="${data?.tags || ''}" placeholder="Contoh: Emergency, Debt, Insurance"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Expected Benefit</label>
                    <input type="number" name="expected_benefit" value="${data?.expected_benefit || 5}" min="1" max="10"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Learning Objective</label>
                <textarea name="learning_objective" rows="2" placeholder="Contoh: Pentingnya memiliki dana darurat untuk kejadian tak terduga."
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">${data?.learning_objective || ''}</textarea>
            </div>
        `;
    }

    formBody.innerHTML = formHtml;
}

async function handleSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    let payload = {};

    // Helper function untuk convert difficulty
    const getDifficultyValue = (diff) => {
        if (diff === 'easy' || diff === '1') return 1;
        if (diff === 'medium' || diff === '2') return 2;
        if (diff === 'hard' || diff === '3') return 3;
        return parseInt(diff);
    };

    if (currentTab === 'scenarios') {
        // Skenario: Kategori, Judul, Pertanyaan, Difficulty, Expected Benefit, 3 Opsi A-C dengan multi-aspek score
        const options = [];
        let i = 0;
        while (formData.has(`option_label_${i}`)) {
            // Build scoreChange object dari multi-aspek
            const scoreChange = {};
            const aspek1 = formData.get(`option_aspek1_${i}`);
            const score1 = parseInt(formData.get(`option_score1_${i}`)) || 0;
            if (aspek1 && score1 !== 0) {
                scoreChange[aspek1] = score1;
            }
            const aspek2 = formData.get(`option_aspek2_${i}`);
            const score2 = parseInt(formData.get(`option_score2_${i}`)) || 0;
            if (aspek2 && score2 !== 0) {
                scoreChange[aspek2] = score2;
            }

            options.push({
                optionId: formData.get(`option_label_${i}`),
                text: formData.get(`option_text_${i}`),
                response: formData.get(`option_response_${i}`) || '',
                is_correct: formData.has(`option_correct_${i}`),
                scoreChange: scoreChange
            });
            i++;
        }

        const diffValue = formData.get('difficulty');
        const difficulty = diffValue ? getDifficultyValue(diffValue) : 1;

        payload = {
            title: formData.get('title') || 'Skenario Baru',
            category: formData.get('category'), // Sub-kategori (Uang bulanan, Freelance, dll)
            question: formData.get('question'),
            difficulty: difficulty,
            expected_benefit: parseInt(formData.get('expected_benefit')) || 5,
            options: options
        };
    } else if (currentTab === 'quiz') {
        const options = [];
        let correctOption = 'A';
        let i = 0;
        while (formData.has(`quiz_label_${i}`)) {
            const label = formData.get(`quiz_label_${i}`);
            const isCorrect = formData.has(`quiz_correct_${i}`);
            if (isCorrect) correctOption = label;
            options.push({
                optionId: label,
                text: formData.get(`quiz_text_${i}`),
                is_correct: isCorrect
            });
            i++;
        }

        const diffValue = formData.get('difficulty');
        const difficulty = diffValue ? getDifficultyValue(diffValue) : 1;

        // Parse tags
        const tagsStr = formData.get('tags') || '';
        const tagsArray = tagsStr.split(',').map(t => t.trim()).filter(t => t);

        payload = {
            question: formData.get('question'),
            difficulty: difficulty,
            correctScore: parseInt(formData.get('correctScore')),
            incorrectScore: parseInt(formData.get('incorrectScore')),
            correctOption: correctOption,
            tags: tagsArray.join(', '),
            learning_objective: formData.get('learning_objective') || '',
            options: options
        };
    } else { // Risk & Chance
        const diffValue = formData.get('difficulty');
        const difficulty = diffValue ? getDifficultyValue(diffValue) : 1;

        // Parse tags from comma-separated string to array
        const tagsStr = formData.get('tags') || '';
        const tagsArray = tagsStr.split(',').map(t => t.trim()).filter(t => t);

        // target_tile is now always required
        const targetTileValue = formData.get('target_tile');
        if (!targetTileValue) {
            alert('Target Tile wajib diisi!');
            return;
        }

        payload = {
            title: formData.get('title'),
            narration: formData.get('narration'),
            action: formData.get('action') || 'default',
            target_tile: parseInt(targetTileValue),
            tags: tagsArray.join(', '),
            difficulty: difficulty,
            expected_benefit: parseInt(formData.get('expected_benefit')) || 5,
            learning_objective: formData.get('learning_objective') || '',
            scoreChange: parseInt(formData.get('scoreChange') || 0)
        };
    }

    console.log('Payload being sent:', payload);

    try {
        let url, method;
        if (isEditMode) {
            method = 'PUT';
            if (currentTab === 'scenarios') url = `${BASE_API}/scenarios/${currentId}`;
            else if (currentTab === 'quiz') url = `${BASE_API}/cards/quiz/${currentId}`;
            else url = `${BASE_API}/cards/${currentTab}/${currentId}`;
        } else {
            method = 'POST';
            if (currentTab === 'scenarios') url = `${BASE_API}/scenarios`;
            else if (currentTab === 'quiz') url = `${BASE_API}/cards/quiz`;
            else url = `${BASE_API}/cards/${currentTab}`;
        }

        const res = await fetch(url, {
            method: method,
            headers: {
                ...headers,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();

        if (!res.ok) {
            console.error('Server response:', result);
            throw new Error(result.message || JSON.stringify(result));
        }

        alert(isEditMode ? 'Data berhasil diupdate!' : 'Data berhasil ditambahkan!');
        closeFormModal();
        loadData();
    } catch (e) {
        console.error('Full error:', e);
        alert('Error: ' + e.message);
    }
}

async function deleteContent() {
    if (!currentId) return;
    if (!confirm('Yakin ingin menghapus konten ini?')) return;

    try {
        let url;
        if (currentTab === 'scenarios') url = `${BASE_API}/scenarios/${currentId}`;
        else if (currentTab === 'quiz') url = `${BASE_API}/cards/quiz/${currentId}`;
        else url = `${BASE_API}/cards/${currentTab}/${currentId}`;

        const res = await fetch(url, {
            method: 'DELETE',
            headers: headers
        });

        if (!res.ok) throw new Error('Gagal menghapus data');

        alert('Data berhasil dihapus!');
        closeModal();
        loadData();
    } catch (e) {
        alert('Error: ' + e.message);
    }
}

// Shortcut functions untuk tombol di tabel
function editItem(id) {
    currentId = id;
    isEditMode = true;
    document.getElementById('form-modal-title').innerText = `Edit ${getContentTypeName()}`;
    renderForm(true);
    document.getElementById('form-modal').classList.remove('hidden');
}

function deleteItem(id) {
    currentId = id;
    deleteContent();
}