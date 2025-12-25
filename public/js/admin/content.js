// Global State
let currentTab = 'scenarios';
const headers = {
    'Authorization': `Bearer ${token}`, // token dari layout
    'Accept': 'application/json'
};

// Init
document.addEventListener('DOMContentLoaded', () => {
    loadData();
});

// --- TAB SWITCHING ---
function switchTab(tab) {
    currentTab = tab;

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
async function loadData(keyword = '') {
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
        if (currentTab === 'scenarios') url = `${BASE_API}/scenarios?limit=10&search=${keyword}`;
        else if (currentTab === 'risk') url = `${BASE_API}/cards/risk?limit=10&search=${keyword}`;
        else if (currentTab === 'chance') url = `${BASE_API}/cards/chance?limit=10&search=${keyword}`;
        else if (currentTab === 'quiz') url = `${BASE_API}/cards/quiz?limit=10&search=${keyword}`;

        const response = await fetch(url, { headers });
        if (!response.ok) throw new Error("Gagal mengambil data");
        const json = await response.json();

        renderTable(json.data || []);

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
        columns = ['Pertanyaan', 'Kategori', 'Bobot', 'Skor', 'Opsi', 'Aksi'];
        data.forEach(item => {
            rows += `
                <tr class="hover:bg-green-50 border-b border-zinc-100 transition-colors">
                    <td class="px-6 py-4 font-semibold text-zinc-800">${item.title}</td>
                    <td class="px-6 py-4"><span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full border border-blue-200 font-medium">${item.category}</span></td>
                    <td class="px-6 py-4">${renderDifficulty(item.difficulty)}</td>
                    <td class="px-6 py-4 text-indigo-600 font-bold">${item.score || '-'}</td>
                    <td class="px-6 py-4 text-zinc-600 font-medium">${item.options_count} Pilihan</td>
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
        columns = ['Pertanyaan', 'Akurasi', 'Total Main', 'Aksi'];
        data.forEach(item => {
            rows += `
                <tr class="hover:bg-green-50 border-b border-zinc-100 transition-colors">
                    <td class="px-6 py-4 text-zinc-800 max-w-md">
                        <div class="truncate font-medium">${item.question}</div>
                    </td>
                    <td class="px-6 py-4"><span class="text-green-600 font-bold">${item.accuracy}</span></td>
                    <td class="px-6 py-4 text-zinc-600 font-medium">${item.total_attempts}x</td>
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
            body.innerHTML = `
                <div class="bg-gray-50 p-4 rounded border border-gray-200 mb-4">
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-2 inline-block">${item.content.category}</span>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">${item.content.title}</h4>
                    <p class="text-gray-700 leading-relaxed">${item.content.question}</p>
                </div>
                <h5 class="font-bold text-gray-700 mb-2 text-sm uppercase tracking-wide">Opsi Jawaban:</h5>
                <ul class="space-y-3">
                    ${item.options.map(opt => `
                        <li class="p-3 border rounded-lg ${opt.is_correct ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200'}">
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-gray-200 font-bold text-xs">${opt.label}</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">${opt.text}</p>
                                    <p class="text-xs text-gray-500 mt-1 italic">"${opt.feedback}"</p>
                                </div>
                                ${opt.is_correct ? '<i class="fa-solid fa-check-circle text-green-600 text-lg"></i>' : ''}
                            </div>
                        </li>
                    `).join('')}
                </ul>
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
                    <div class="bg-white px-3 py-1 rounded border">Kesulitan: <strong>${item.difficulty}</strong></div>
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
            `;

            // 3. DETAIL RISK / CHANCE
        } else {
            const isRisk = currentTab === 'risk';
            const impactVal = isRisk ? item.impact : item.benefit;
            const impactColor = isRisk ? 'text-red-600' : 'text-green-600';
            const bgHeader = isRisk ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200';

            title.innerText = isRisk ? 'Kartu Risiko' : 'Kartu Kesempatan';
            body.innerHTML = `
                <div class="${bgHeader} p-5 rounded-lg border text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">${item.title}</h2>
                    <p class="text-gray-600 italic">"${item.description}"</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded shadow-sm border border-gray-100 text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Efek Score</p>
                        <span class="text-3xl font-bold ${impactColor}">${impactVal > 0 ? '+' + impactVal : impactVal}</span>
                    </div>
                    <div class="bg-white p-4 rounded shadow-sm border border-gray-100 text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Statistik</p>
                        <span class="text-xl font-bold text-gray-800">${item.stats.landed_count}</span>
                        <span class="text-xs text-gray-500 block">Kali Muncul</span>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded-full">Aksi: ${item.action_type || 'default'}</span>
                </div>
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
    // Set initial score based on default Bobot
    if (currentTab === 'scenarios') {
        setTimeout(() => updateScoreRange(), 100);
    }
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
        const diffValue = data?.content?.difficulty || 1;
        const scoreValue = data?.content?.score || 10;
        formHtml = `
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Pertanyaan</label>
                    <input type="text" name="title" value="${data?.content?.title || ''}" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Judul/Pertanyaan singkat">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                    <input type="text" name="category" value="${data?.content?.category || ''}" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Contoh: Pendapatan, Anggaran">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Bobot</label>
                    <select name="difficulty" id="bobot-select" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" onchange="updateScoreRange()">
                        <option value="1" ${diffValue == 1 ? 'selected' : ''}>Sangat Mudah (6-15)</option>
                        <option value="2" ${diffValue == 2 ? 'selected' : ''}>Mudah (21-35)</option>
                        <option value="3" ${diffValue == 3 ? 'selected' : ''}>Sedang (41-60)</option>
                        <option value="4" ${diffValue == 4 ? 'selected' : ''}>Sulit (67-85)</option>
                        <option value="5" ${diffValue == 5 ? 'selected' : ''}>Sangat Sulit (88-100)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Skor <span id="score-range-label" class="text-sm text-gray-500">(6-15)</span></label>
                    <input type="number" name="score" id="score-input" value="${scoreValue}" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Skor pertanyaan">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Detail Pertanyaan</label>
                <textarea name="question" rows="2" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" placeholder="Deskripsi lengkap pertanyaan">${data?.content?.question || ''}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Opsi Jawaban (5 Pilihan: A-E)</label>
                <div id="options-container" class="space-y-2">
                    ${data?.options ? data.options.map((opt, i) => `
                        <div class="flex gap-2 items-center border p-2 rounded bg-gray-50">
                            <span class="font-bold text-indigo-600 w-8">${opt.label}.</span>
                            <input type="hidden" name="option_label_${i}" value="${opt.label}">
                            <input type="text" name="option_text_${i}" value="${opt.text}" placeholder="Teks opsi ${opt.label}" required class="flex-1 px-2 py-1 border rounded">
                        </div>
                    `).join('') : `
                        <div class="flex gap-2 items-center border p-2 rounded bg-gray-50">
                            <span class="font-bold text-indigo-600 w-8">A.</span>
                            <input type="hidden" name="option_label_0" value="A">
                            <input type="text" name="option_text_0" placeholder="Teks opsi A" required class="flex-1 px-2 py-1 border rounded">
                        </div>
                        <div class="flex gap-2 items-center border p-2 rounded bg-gray-50">
                            <span class="font-bold text-indigo-600 w-8">B.</span>
                            <input type="hidden" name="option_label_1" value="B">
                            <input type="text" name="option_text_1" placeholder="Teks opsi B" required class="flex-1 px-2 py-1 border rounded">
                        </div>
                        <div class="flex gap-2 items-center border p-2 rounded bg-gray-50">
                            <span class="font-bold text-indigo-600 w-8">C.</span>
                            <input type="hidden" name="option_label_2" value="C">
                            <input type="text" name="option_text_2" placeholder="Teks opsi C" required class="flex-1 px-2 py-1 border rounded">
                        </div>
                        <div class="flex gap-2 items-center border p-2 rounded bg-gray-50">
                            <span class="font-bold text-indigo-600 w-8">D.</span>
                            <input type="hidden" name="option_label_3" value="D">
                            <input type="text" name="option_text_3" placeholder="Teks opsi D" required class="flex-1 px-2 py-1 border rounded">
                        </div>
                        <div class="flex gap-2 items-center border p-2 rounded bg-gray-50">
                            <span class="font-bold text-indigo-600 w-8">E.</span>
                            <input type="hidden" name="option_label_4" value="E">
                            <input type="text" name="option_text_4" placeholder="Teks opsi E" required class="flex-1 px-2 py-1 border rounded">
                        </div>
                    `}
                </div>
            </div>
        `;
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
                        <option value="1" ${diffValue == 1 ? 'selected' : ''}>Easy</option>
                        <option value="2" ${diffValue == 2 ? 'selected' : ''}>Medium</option>
                        <option value="3" ${diffValue == 3 ? 'selected' : ''}>Hard</option>
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
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Opsi Jawaban</label>
                <div id="quiz-options">
                    ${data?.options ? data.options.map((opt, i) => `
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="quiz_label_${i}" value="${opt.label}" placeholder="A" required class="w-16 px-2 py-1 border rounded">
                            <input type="text" name="quiz_text_${i}" value="${opt.text}" placeholder="Teks jawaban" required class="flex-1 px-2 py-1 border rounded">
                            <label class="flex items-center">
                                <input type="checkbox" name="quiz_correct_${i}" ${opt.label === data.correct_option_id ? 'checked' : ''} class="mr-1">
                                <span class="text-xs">Benar</span>
                            </label>
                        </div>
                    `).join('') : `
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="quiz_label_0" placeholder="A" value="A" required class="w-16 px-2 py-1 border rounded">
                            <input type="text" name="quiz_text_0" placeholder="Teks jawaban" required class="flex-1 px-2 py-1 border rounded">
                            <label class="flex items-center">
                                <input type="checkbox" name="quiz_correct_0" class="mr-1">
                                <span class="text-xs">Benar</span>
                            </label>
                        </div>
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="quiz_label_1" placeholder="B" value="B" required class="w-16 px-2 py-1 border rounded">
                            <input type="text" name="quiz_text_1" placeholder="Teks jawaban" required class="flex-1 px-2 py-1 border rounded">
                            <label class="flex items-center">
                                <input type="checkbox" name="quiz_correct_1" class="mr-1">
                                <span class="text-xs">Benar</span>
                            </label>
                        </div>
                    `}
                </div>
            </div>
        `;
    } else { // Risk & Chance
        const diffValue = data?.difficulty || 1;
        formHtml = `
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul</label>
                <input type="text" name="title" value="${data?.title || ''}" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi/Efek</label>
                <textarea name="effect" rows="3" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">${data?.description || ''}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kesulitan</label>
                    <select name="difficulty" required class="w-full px-3 py-2 border rounded-lg">
                        <option value="1" ${diffValue == 1 ? 'selected' : ''}>Easy</option>
                        <option value="2" ${diffValue == 2 ? 'selected' : ''}>Medium</option>
                        <option value="3" ${diffValue == 3 ? 'selected' : ''}>Hard</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Dampak Score</label>
                    <input type="number" name="scoreChange" value="${data?.impact || data?.benefit || 0}" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>
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
        const options = [];
        let i = 0;
        while (formData.has(`option_label_${i}`)) {
            options.push({
                optionId: formData.get(`option_label_${i}`),
                text: formData.get(`option_text_${i}`),
                response: '-',
                is_correct: false,
                scoreChange: {}
            });
            i++;
        }

        const diffValue = formData.get('difficulty');
        const difficulty = diffValue ? parseInt(diffValue) : 1;
        const score = parseInt(formData.get('score') || 10);

        payload = {
            title: formData.get('title'),
            category: formData.get('category'),
            difficulty: difficulty,
            expected_benefit: score,
            question: formData.get('question'),
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

        payload = {
            question: formData.get('question'),
            difficulty: difficulty,
            correctScore: parseInt(formData.get('correctScore')),
            incorrectScore: parseInt(formData.get('incorrectScore')),
            correctOption: correctOption,
            options: options
        };
    } else { // Risk & Chance
        const diffValue = formData.get('difficulty');
        const difficulty = diffValue ? getDifficultyValue(diffValue) : 1;

        payload = {
            title: formData.get('title'),
            effect: formData.get('effect'),
            difficulty: difficulty,
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