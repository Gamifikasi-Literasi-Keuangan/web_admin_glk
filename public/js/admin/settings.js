const headers = {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
};
let currentTab = 'config';

document.addEventListener('DOMContentLoaded', () => loadData());

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById(`tab-${tab}`).classList.remove('border-transparent', 'text-gray-500');
    document.getElementById(`tab-${tab}`).classList.add('border-indigo-500', 'text-indigo-600');
    
    loadData();
}

async function loadData() {
    const container = document.getElementById('settings-content');
    container.innerHTML = '<div class="loader"></div>';

    try {
        let url;
        if (currentTab === 'config') url = `${BASE_API}/config/game`;
        else if (currentTab === 'tiles') url = `${BASE_API}/tiles`;
        else url = `${BASE_API}/interventions`; // Fix: Pakai BASE_API

        const res = await fetch(url, { headers });
        
        // Handle Error Response
        if (!res.ok) throw new Error(`Gagal mengambil data (${res.status})`);

        const json = await res.json();
        const data = json.data || json;

        if (currentTab === 'config') renderConfig(data);
        else if (currentTab === 'tiles') renderTiles(data);
        else renderInterventions(data);

    } catch (e) {
        container.innerHTML = `<div class="text-red-500 p-4">Error: ${e.message}</div>`;
    }
}

// --- 1. RENDER CONFIG ---
function renderConfig(data) {
    const container = document.getElementById('settings-content');
    container.innerHTML = `
        <div class="max-w-lg">
            <h3 class="text-lg font-bold mb-4 text-gray-700">Aturan Dasar Permainan</h3>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-50 p-4 rounded border">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Maksimal Pemain</label>
                    <input type="number" value="${data.max_players || 4}" readonly class="bg-gray-200 w-full p-2 rounded border text-gray-500">
                </div>
                <div class="bg-gray-50 p-4 rounded border">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Batas Giliran</label>
                    <input type="number" value="${data.turn_limit || 50}" readonly class="bg-gray-200 w-full p-2 rounded border text-gray-500">
                </div>
                <div class="bg-gray-50 p-4 rounded border">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Versi Config</label>
                    <span class="text-indigo-600 font-mono">v${data.version || 1}</span>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-4">* Read-Only. Hubungi developer untuk update.</p>
        </div>
    `;
}

// --- 2. RENDER TILES (PETA) ---
function renderTiles(tiles) {
    const container = document.getElementById('settings-content');
    if (!tiles.length) { container.innerHTML = 'Data Peta kosong.'; return; }

    let rows = tiles.map(t => `
        <tr class="hover:bg-gray-50 border-b transition">
            <td class="px-4 py-3 text-center font-bold text-gray-600">${t.position}</td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 rounded text-xs font-bold uppercase 
                    ${t.type === 'risk' ? 'bg-red-100 text-red-700' : 
                      (t.type === 'chance' ? 'bg-green-100 text-green-700' : 
                      (t.type === 'quiz' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700'))}">
                    ${t.type}
                </span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-800">${t.name || '-'}</td>
            <td class="px-4 py-3 text-xs font-mono text-gray-500">${t.content_id ? 'ID: ' + t.content_id : '(Random/Empty)'}</td>
            <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fa-solid fa-user-check mr-1"></i> ${t.landed_count || 0}x
                </span>
            </td>
            <td class="px-4 py-3 text-right">
                <button onclick="showTileDetail('${t.tile_id}')" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold border border-indigo-600 px-2 py-1 rounded hover:bg-indigo-50">
                    Lihat
                </button>
            </td>
        </tr>
    `).join('');

    container.innerHTML = `
        <h3 class="text-lg font-bold mb-4 text-gray-700">Peta Papan (Read Only)</h3>
        <p class="text-sm text-gray-500 mb-4"><i class="fa-solid fa-info-circle mr-1"></i> Statistik pendaratan menunjukkan berapa kali tiles dikunjungi oleh pemain</p>
        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center">Pos</th>
                        <th class="px-4 py-3 text-left">Tipe</th>
                        <th class="px-4 py-3 text-left">Label</th>
                        <th class="px-4 py-3 text-left">Konten</th>
                        <th class="px-4 py-3 text-center"><i class="fa-solid fa-chart-bar mr-1"></i> Pendaratan</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
    `;
}

// --- 3. RENDER INTERVENTIONS (AI) ---
function renderInterventions(items) {
    const container = document.getElementById('settings-content');
    if (!items.length) { container.innerHTML = 'Template kosong.'; return; }

    let cards = items.map(i => `
        <div class="border-l-4 ${i.ui_color === 'red' ? 'border-red-500' : (i.ui_color === 'orange' ? 'border-orange-500' : 'border-yellow-500')} bg-white shadow-sm rounded p-4 mb-4 transition hover:shadow-md">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Level ${i.level_id} • ${i.risk_label}</span>
                    <h4 class="text-md font-bold text-gray-800 mt-1">${i.title}</h4>
                    <p class="text-gray-600 mt-2 text-sm italic">"${i.message}"</p>
                </div>
                ${i.is_mandatory ? '<span class="bg-red-100 text-red-800 text-[10px] px-2 py-1 rounded font-bold"><i class="fa-solid fa-lock mr-1"></i> WAJIB</span>' : ''}
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 flex gap-2 flex-wrap">
                <span class="text-xs text-gray-400 self-center mr-2">Tombol:</span>
                ${i.actions ? i.actions.map(a => `<span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded border border-gray-300">${a.text}</span>`).join('') : '-'}
            </div>
        </div>
    `).join('');

    container.innerHTML = `
        <h3 class="text-lg font-bold mb-4 text-gray-700">Template Pesan AI (Intervensi)</h3>
        <div class="grid grid-cols-1 gap-2 max-w-3xl">${cards}</div>
    `;
}

// --- 4. LOGIC MODAL TILE DETAIL ---
async function showTileDetail(id) {
    const modal = document.getElementById('tile-modal');
    const body = document.getElementById('modal-body');
    
    modal.classList.remove('hidden'); // Tampilkan Modal
    body.innerHTML = '<div class="loader"></div>';

    try {
        // Panggil API Detail Tile
        const res = await fetch(`${BASE_API}/tiles/${id}`, { headers });
        const json = await res.json();
        const t = json.data || json; // Handle wrapper

        // Render Isi Modal
        body.innerHTML = `
            <div class="space-y-4">
                <div class="text-center">
                    <span class="inline-block p-3 rounded-full bg-gray-100 mb-2">
                        <i class="fa-solid fa-map-marker-alt text-2xl text-indigo-600"></i>
                    </span>
                    <h4 class="text-xl font-bold text-gray-800">${t.name || t.default_name || t.tile_id}</h4>
                    <span class="text-xs font-mono text-gray-500">ID: ${t.tile_id}</span>
                </div>
                
                <div class="bg-gray-50 p-3 rounded border">
                    <p class="text-xs text-gray-500 uppercase">Tipe Kotak</p>
                    <p class="font-bold text-gray-800">${t.type}</p>
                </div>

                <div class="bg-gray-50 p-3 rounded border">
                    <p class="text-xs text-gray-500 uppercase">Konten Tertaut</p>
                    <p class="font-medium text-indigo-600">${t.content_title || '-'}</p>
                    <p class="text-xs text-gray-400">${t.content_id ? (t.content_type + ': ' + t.content_id) : 'Tidak ada konten khusus'}</p>
                </div>

                <div class="bg-blue-50 p-3 rounded border border-blue-100 text-center">
                    <p class="text-xs text-blue-500 uppercase mb-2"><i class="fa-solid fa-chart-line mr-1"></i> Statistik Pendaratan</p>
                    <p class="text-3xl font-bold text-blue-700">${t.landed_count || 0}</p>
                    <p class="text-xs text-blue-600 mt-1">kali dikunjungi pemain</p>
                </div>
            </div>
        `;

    } catch (e) {
        body.innerHTML = `<p class="text-red-500">Gagal memuat detail: ${e.message}</p>`;
    }
}

function closeModal() {
    document.getElementById('tile-modal').classList.add('hidden');
}