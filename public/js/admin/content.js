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
                        <button onclick="showDetail('${item.id}')" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">Lihat</button>
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
                        <button onclick="showDetail('${item.id}')" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">Lihat</button>
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
                    <td class="px-5 py-4 ${color} font-bold">${effect > 0 ? '+'+effect : effect}</td>
                    <td class="px-5 py-4 text-sm">${renderDifficulty(item.difficulty)}</td>
                    <td class="px-5 py-4 text-sm">${item.usage}x</td>
                    <td class="px-5 py-4">
                        <button onclick="showDetail('${item.id}')" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">Lihat</button>
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
    if(level === 1) return '<span class="text-green-500 font-bold">Easy</span>';
    if(level === 2) return '<span class="text-yellow-500 font-bold">Medium</span>';
    return '<span class="text-red-500 font-bold">Hard</span>';
}

let timeout;
function handleSearch(val) {
    clearTimeout(timeout);
    timeout = setTimeout(() => loadData(val), 500);
}

// --- MODAL DETAIL (PERBAIKAN TAMPILAN) ---
async function showDetail(id) {
    const modal = document.getElementById('detail-modal');
    const body = document.getElementById('modal-body');
    const title = document.getElementById('modal-title');
    
    modal.classList.remove('hidden');
    body.innerHTML = '<div class="loader"></div>';
    
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
                        <span class="text-3xl font-bold ${impactColor}">${impactVal > 0 ? '+'+impactVal : impactVal}</span>
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