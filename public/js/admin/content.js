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

    // Update UI Tab Active
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById(`tab-${tab}`).classList.remove('border-transparent', 'text-gray-500');
    document.getElementById(`tab-${tab}`).classList.add('border-indigo-500', 'text-indigo-600');

    // Reset Search & Load Data
    document.getElementById('searchInput').value = '';
    loadData();
}

// --- DATA LOADING ---
async function loadData(keyword = '') {
    const wrapper = document.getElementById('table-wrapper');
    wrapper.innerHTML = '<div class="loader mt-10"></div>';

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
        wrapper.innerHTML = `<div class="text-red-500 p-10 text-center">Error: ${e.message}</div>`;
    }
}

// --- RENDER TABLE ---
function renderTable(data) {
    const wrapper = document.getElementById('table-wrapper');

    if (data.length === 0) {
        wrapper.innerHTML = `<div class="p-10 text-center text-gray-500">Data tidak ditemukan.</div>`;
        return;
    }

    let columns = [];
    let rows = '';

    // Tentukan Kolom berdasarkan Tab
    if (currentTab === 'scenarios') {
        columns = ['Judul', 'Kategori', 'Kesulitan', 'Opsi', 'Aksi'];
        data.forEach(item => {
            rows += `
                <tr class="hover:bg-gray-50 border-b">
                    <td class="px-5 py-4 font-bold text-gray-800">${item.title}</td>
                    <td class="px-5 py-4"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">${item.category}</span></td>
                    <td class="px-5 py-4 text-sm">${renderDifficulty(item.difficulty)}</td>
                    <td class="px-5 py-4 text-sm text-gray-500">${item.options_count} Pilihan</td>
                    <td class="px-5 py-4">
                        <div class="flex gap-2">
                            <button onclick="showDetail('${item.id}')" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                                <i class="fa-solid fa-eye mr-1"></i>Lihat
                            </button>
                            <button onclick="editItem('${item.id}')" class="text-yellow-600 hover:text-yellow-900 font-bold text-sm">
                                <i class="fa-solid fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteItem('${item.id}')" class="text-red-600 hover:text-red-900 font-bold text-sm">
                                <i class="fa-solid fa-trash mr-1"></i>Hapus
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
                <tr class="hover:bg-gray-50 border-b">
                    <td class="px-5 py-4 text-sm text-gray-800 max-w-md truncate">${item.question}</td>
                    <td class="px-5 py-4"><span class="text-green-600 font-bold">${item.accuracy}</span></td>
                    <td class="px-5 py-4 text-sm">${item.total_attempts}x</td>
                    <td class="px-5 py-4">
                        <div class="flex gap-2">
                            <button onclick="showDetail('${item.id}')" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                                <i class="fa-solid fa-eye mr-1"></i>Lihat
                            </button>
                            <button onclick="editItem('${item.id}')" class="text-yellow-600 hover:text-yellow-900 font-bold text-sm">
                                <i class="fa-solid fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteItem('${item.id}')" class="text-red-600 hover:text-red-900 font-bold text-sm">
                                <i class="fa-solid fa-trash mr-1"></i>Hapus
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
                <tr class="hover:bg-gray-50 border-b">
                    <td class="px-5 py-4 font-bold text-gray-800">${item.title}</td>
                    <td class="px-5 py-4 ${color} font-bold">${effect > 0 ? '+' + effect : effect}</td>
                    <td class="px-5 py-4 text-sm">${renderDifficulty(item.difficulty)}</td>
                    <td class="px-5 py-4 text-sm">${item.usage}x</td>
                    <td class="px-5 py-4">
                        <div class="flex gap-2">
                            <button onclick="showDetail('${item.id}')" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                                <i class="fa-solid fa-eye mr-1"></i>Lihat
                            </button>
                            <button onclick="editItem('${item.id}')" class="text-yellow-600 hover:text-yellow-900 font-bold text-sm">
                                <i class="fa-solid fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteItem('${item.id}')" class="text-red-600 hover:text-red-900 font-bold text-sm">
                                <i class="fa-solid fa-trash mr-1"></i>Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    let headerHtml = columns.map(c => `<th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">${c}</th>`).join('');

    wrapper.innerHTML = `
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead><tr>${headerHtml}</tr></thead>
                <tbody class="bg-white divide-y divide-gray-200">${rows}</tbody>
            </table>
        </div>
    `;
}

// --- UTILS ---
function renderDifficulty(level) {
    if (level === 1) return '<span class="text-green-500 font-bold">Easy</span>';
    if (level === 2) return '<span class="text-yellow-500 font-bold">Medium</span>';
    return '<span class="text-red-500 font-bold">Hard</span>';
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
        formHtml = `
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul</label>
                <input type="text" name="title" value="${data?.content?.title || ''}" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                    <input type="text" name="category" value="${data?.content?.category || ''}" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kesulitan</label>
                    <select name="difficulty" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="1" ${diffValue == 1 ? 'selected' : ''}>Easy</option>
                        <option value="2" ${diffValue == 2 ? 'selected' : ''}>Medium</option>
                        <option value="3" ${diffValue == 3 ? 'selected' : ''}>Hard</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Pertanyaan</label>
                <textarea name="question" rows="3" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500">${data?.content?.question || ''}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Opsi Jawaban (Min 2)</label>
                <div id="options-container">
                    ${data?.options ? data.options.map((opt, i) => `
                        <div class="border p-3 rounded mb-2 bg-gray-50">
                            <input type="text" name="option_label_${i}" value="${opt.label}" placeholder="Label (A/B/C)" required class="w-20 px-2 py-1 border rounded mb-2">
                            <input type="text" name="option_text_${i}" value="${opt.text}" placeholder="Teks opsi" required class="w-full px-2 py-1 border rounded mb-2">
                            <input type="text" name="option_feedback_${i}" value="${opt.feedback}" placeholder="Feedback" class="w-full px-2 py-1 border rounded mb-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="option_correct_${i}" ${opt.is_correct ? 'checked' : ''} class="mr-2">
                                <span class="text-sm">Jawaban benar</span>
                            </label>
                        </div>
                    `).join('') : `
                        <div class="border p-3 rounded mb-2 bg-gray-50">
                            <input type="text" name="option_label_0" placeholder="Label (A)" required class="w-20 px-2 py-1 border rounded mb-2">
                            <input type="text" name="option_text_0" placeholder="Teks opsi" required class="w-full px-2 py-1 border rounded mb-2">
                            <input type="text" name="option_feedback_0" placeholder="Feedback" class="w-full px-2 py-1 border rounded mb-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="option_correct_0" class="mr-2">
                                <span class="text-sm">Jawaban benar</span>
                            </label>
                        </div>
                        <div class="border p-3 rounded mb-2 bg-gray-50">
                            <input type="text" name="option_label_1" placeholder="Label (B)" required class="w-20 px-2 py-1 border rounded mb-2">
                            <input type="text" name="option_text_1" placeholder="Teks opsi" required class="w-full px-2 py-1 border rounded mb-2">
                            <input type="text" name="option_feedback_1" placeholder="Feedback" class="w-full px-2 py-1 border rounded mb-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="option_correct_1" class="mr-2">
                                <span class="text-sm">Jawaban benar</span>
                            </label>
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
            const responseText = formData.get(`option_feedback_${i}`);
            options.push({
                optionId: formData.get(`option_label_${i}`),
                text: formData.get(`option_text_${i}`),
                response: responseText && responseText.trim() !== '' ? responseText : 'No feedback',
                is_correct: formData.has(`option_correct_${i}`),
                scoreChange: {}
            });
            i++;
        }

        const diffValue = formData.get('difficulty');
        const difficulty = diffValue ? getDifficultyValue(diffValue) : 1;

        payload = {
            title: formData.get('title'),
            category: formData.get('category'),
            difficulty: difficulty,
            question: formData.get('question'),
            options: options
        };
    } else if (currentTab === 'quiz') {
        const options = [];
        let correctOption = 'A';
        let i = 0;
        while (formData.has(`quiz_label_${i}`)) {
            const label = formData.get(`quiz_label_${i}`);
            if (formData.has(`quiz_correct_${i}`)) correctOption = label;
            options.push({
                optionId: label,
                text: formData.get(`quiz_text_${i}`)
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