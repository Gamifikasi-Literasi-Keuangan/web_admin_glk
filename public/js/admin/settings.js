const headers = {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
};
let currentTab = 'config';

document.addEventListener('DOMContentLoaded', () => loadData());

function switchTab(tab) {
    currentTab = tab;
    
    // Reset semua tab ke state inactive
    document.querySelectorAll('.tab-btn').forEach(btn => {
        // Reset ke state inactive: background putih, text zinc, border zinc
        btn.className = 'tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-800 border border-zinc-200 hover:border-zinc-300';
        
        // Update icon untuk state inactive
        const iconContainer = btn.querySelector('div');
        const icon = btn.querySelector('i');
        if (iconContainer && icon) {
            iconContainer.className = 'bg-zinc-100 p-1.5 rounded-full mr-3';
            icon.className = icon.className.replace(/text-\w+-\w+/, 'text-zinc-500');
        }
    });
    
    // Set tab aktif dengan style hijau
    const activeTab = document.getElementById(`tab-${tab}`);
    if (activeTab) {
        activeTab.className = 'tab-btn flex items-center px-6 py-3 rounded-md font-semibold text-sm transition-all duration-200 bg-green-500 text-white shadow-md';
        
        // Update icon untuk state active
        const iconContainer = activeTab.querySelector('div');
        const icon = activeTab.querySelector('i');
        if (iconContainer && icon) {
            iconContainer.className = 'bg-white bg-opacity-20 p-1.5 rounded-full mr-3';
            icon.className = icon.className.replace(/text-\w+-\w+/, 'text-white');
        }
    }

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
        container.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-lg">
            <i class="fa-solid fa-exclamation-triangle mr-2"></i>Error: ${e.message}
        </div>`;
    }
}

// --- 1. RENDER CONFIG ---
function renderConfig(data) {
    const container = document.getElementById('settings-content');
    container.innerHTML = `
        <div class="max-w-2xl">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fa-solid fa-cog text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-800">Aturan Dasar Permainan</h3>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                            <i class="fa-solid fa-users text-zinc-600"></i>
                        </div>
                        <label class="block text-zinc-700 font-semibold">Maksimal Pemain</label>
                    </div>
                    <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-3">
                        <span class="text-2xl font-bold text-green-600">${data.max_players || 4}</span>
                        <span class="text-zinc-500 ml-2">pemain</span>
                    </div>
                </div>
                
                <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                            <i class="fa-solid fa-clock text-zinc-600"></i>
                        </div>
                        <label class="block text-zinc-700 font-semibold">Batas Giliran</label>
                    </div>
                    <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-3">
                        <span class="text-2xl font-bold text-green-600">${data.turn_limit || 50}</span>
                        <span class="text-zinc-500 ml-2">turn</span>
                    </div>
                </div>
                
                <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow md:col-span-2">
                    <div class="flex items-center mb-3">
                        <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                            <i class="fa-solid fa-code-branch text-zinc-600"></i>
                        </div>
                        <label class="block text-zinc-700 font-semibold">Versi Konfigurasi</label>
                    </div>
                    <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-3">
                        <span class="text-xl font-mono font-bold text-green-600">v${data.version || 1}</span>
                        <span class="text-zinc-500 ml-2">• Aktif</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
                <div class="flex items-center">
                    <i class="fa-solid fa-info-circle text-yellow-600 mr-2"></i>
                    <p class="text-sm text-yellow-700">
                        <strong>Read-Only Configuration</strong> - Hubungi developer untuk melakukan perubahan pengaturan.
                    </p>
                </div>
            </div>
        </div>
    `;
}

// --- 2. RENDER TILES (PETA) ---
function renderTiles(tiles) {
    const container = document.getElementById('settings-content');
    if (!tiles.length) { 
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="bg-zinc-100 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fa-solid fa-map text-zinc-400 text-xl"></i>
                </div>
                <p class="text-zinc-500">Data Peta kosong.</p>
            </div>
        `; 
        return; 
    }

    let rows = tiles.map(t => `
        <tr class="hover:bg-green-50 border-b border-zinc-200 transition-colors">
            <td class="px-4 py-3 text-center">
                <span class="bg-green-100 text-green-700 font-bold px-2 py-1 rounded-full text-sm">${t.position}</span>
            </td>
            <td class="px-4 py-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase 
                    ${t.type === 'risk' ? 'bg-red-100 text-red-700 border border-red-200' :
            (t.type === 'chance' ? 'bg-green-100 text-green-700 border border-green-200' :
                (t.type === 'quiz' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 'bg-blue-100 text-blue-700 border border-blue-200'))}">
                    <i class="fa-solid fa-${t.type === 'risk' ? 'triangle-exclamation' : (t.type === 'chance' ? 'clover' : (t.type === 'quiz' ? 'question-circle' : 'circle'))} mr-1"></i>
                    ${t.type}
                </span>
            </td>
            <td class="px-4 py-3">
                <span class="text-zinc-800 font-medium">${t.name || '-'}</span>
            </td>
            <td class="px-4 py-3">
                <span class="text-xs font-mono text-zinc-500 bg-zinc-50 px-2 py-1 rounded border border-zinc-200">
                    ${t.content_id ? 'ID: ' + t.content_id : 'Random/Empty'}
                </span>
            </td>
            <td class="px-4 py-3 text-center">
                <div class="inline-flex items-center bg-green-50 border border-green-200 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-chart-bar text-green-600 mr-2"></i>
                    <span class="text-green-700 font-semibold">${t.landed_count || 0}</span>
                    <span class="text-green-600 text-xs ml-1">x</span>
                </div>
            </td>
            <td class="px-4 py-3 text-right">
                <button onclick="showTileDetail('${t.tile_id}')" 
                    class="bg-green-100 hover:bg-green-200 text-green-700 hover:text-green-800 px-3 py-1.5 rounded-lg font-medium text-sm transition-colors border border-green-200 hover:border-green-300">
                    <i class="fa-solid fa-eye mr-1"></i>Lihat
                </button>
            </td>
        </tr>
    `).join('');

    container.innerHTML = `
        <div class="space-y-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center mb-2">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fa-solid fa-map text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-800">Peta Papan Permainan</h3>
                </div>
                <p class="text-zinc-600 ml-16">
                    <i class="fa-solid fa-info-circle text-green-600 mr-2"></i>
                    Statistik pendaratan menunjukkan berapa kali tiles dikunjungi oleh pemain
                </p>
            </div>
            
            <div class="bg-white border border-zinc-200 rounded-lg overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-zinc-50 border-b border-zinc-200">
                            <tr class="text-zinc-600 uppercase text-xs font-semibold tracking-wide">
                                <th class="px-4 py-4 text-center">Posisi</th>
                                <th class="px-4 py-4 text-left">Tipe Tile</th>
                                <th class="px-4 py-4 text-left">Label</th>
                                <th class="px-4 py-4 text-left">Konten</th>
                                <th class="px-4 py-4 text-center">
                                    <i class="fa-solid fa-chart-bar mr-1"></i>Pendaratan
                                </th>
                                <th class="px-4 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">${rows}</tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}

// --- 3. RENDER INTERVENTIONS (AI) ---
function renderInterventions(items) {
    const container = document.getElementById('settings-content');
    if (!items.length) { 
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="bg-zinc-100 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                    <i class="fa-solid fa-robot text-zinc-400 text-xl"></i>
                </div>
                <p class="text-zinc-500">Template AI kosong.</p>
            </div>
        `; 
        return; 
    }

    let cards = items.map(i => `
        <div class="bg-white border border-zinc-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="border-l-4 ${i.ui_color === 'red' ? 'border-red-500' : (i.ui_color === 'orange' ? 'border-orange-500' : 'border-yellow-500')} p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="bg-zinc-100 text-zinc-600 text-xs font-semibold px-2 py-1 rounded-full mr-3">
                                Level ${i.level_id}
                            </span>
                            <span class="bg-${i.ui_color === 'red' ? 'red' : (i.ui_color === 'orange' ? 'orange' : 'yellow')}-100 text-${i.ui_color === 'red' ? 'red' : (i.ui_color === 'orange' ? 'orange' : 'yellow')}-700 text-xs font-semibold px-2 py-1 rounded-full">
                                ${i.risk_label}
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-zinc-800 mb-3">${i.title}</h4>
                        <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-4">
                            <p class="text-zinc-700 italic leading-relaxed">"${i.message}"</p>
                        </div>
                    </div>
                    ${i.is_mandatory ? `
                        <div class="ml-4">
                            <span class="bg-red-100 border border-red-200 text-red-800 text-xs px-3 py-1 rounded-full font-semibold">
                                <i class="fa-solid fa-lock mr-1"></i>WAJIB
                            </span>
                        </div>
                    ` : ''}
                </div>
                
                <div class="pt-4 border-t border-zinc-100">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="fa-solid fa-mouse-pointer text-green-600"></i>
                        </div>
                        <span class="text-sm font-semibold text-zinc-700">Tombol Aksi:</span>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        ${i.actions ? i.actions.map(a => `
                            <span class="bg-green-50 border border-green-200 text-green-700 text-sm px-3 py-2 rounded-lg font-medium">
                                ${a.text}
                            </span>
                        `).join('') : '<span class="text-zinc-400 text-sm">Tidak ada aksi</span>'}
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    container.innerHTML = `
        <div class="space-y-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center mb-2">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fa-solid fa-robot text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-800">Template Pesan AI (Intervensi)</h3>
                </div>
                <p class="text-zinc-600 ml-16">
                    <i class="fa-solid fa-info-circle text-green-600 mr-2"></i>
                    Konfigurasi pesan otomatis yang akan ditampilkan AI berdasarkan kondisi permainan
                </p>
            </div>
            
            <div class="grid grid-cols-1 gap-6">
                ${cards}
            </div>
        </div>
    `;
}

// --- 4. LOGIC MODAL TILE DETAIL ---
async function showTileDetail(id) {
    const modal = document.getElementById('tile-modal');
    const body = document.getElementById('modal-body');

    modal.classList.remove('hidden'); // Tampilkan Modal
    body.innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
            <p class="text-zinc-500 mt-2">Memuat data...</p>
        </div>
    `;

    try {
        // Panggil API Detail Tile
        const res = await fetch(`${BASE_API}/tiles/${id}`, { headers });
        const json = await res.json();
        const t = json.data || json; // Handle wrapper

        // Render Isi Modal
        body.innerHTML = `
            <div class="space-y-6">
                <div class="text-center bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                    <div class="bg-green-100 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fa-solid fa-map-marker-alt text-2xl text-green-600"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-zinc-800 mb-2">${t.name || t.default_name || t.tile_id}</h4>
                    <span class="bg-zinc-100 border border-zinc-200 text-zinc-600 text-sm font-mono px-3 py-1 rounded-full">
                        ID: ${t.tile_id}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white border border-zinc-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                                <i class="fa-solid fa-tag text-zinc-600"></i>
                            </div>
                            <p class="text-sm font-semibold text-zinc-600 uppercase tracking-wide">Tipe Kotak</p>
                        </div>
                        <p class="text-lg font-bold text-zinc-800">${t.type}</p>
                    </div>

                    <div class="bg-white border border-zinc-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                                <i class="fa-solid fa-link text-zinc-600"></i>
                            </div>
                            <p class="text-sm font-semibold text-zinc-600 uppercase tracking-wide">Konten Tertaut</p>
                        </div>
                        <p class="font-medium text-green-600 mb-1">${t.content_title || 'Tidak ada konten'}</p>
                        <p class="text-xs text-zinc-500 bg-zinc-50 px-2 py-1 rounded border border-zinc-200">
                            ${t.content_id ? (t.content_type + ': ' + t.content_id) : 'Random/Empty content'}
                        </p>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6 text-center">
                    <div class="flex items-center justify-center mb-3">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <i class="fa-solid fa-chart-line text-green-600"></i>
                        </div>
                        <p class="text-sm font-semibold text-green-700 uppercase tracking-wide">
                            Statistik Pendaratan
                        </p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-4xl font-bold text-green-700">${t.landed_count || 0}</p>
                        <p class="text-green-600 font-medium">kali dikunjungi pemain</p>
                    </div>
                </div>
            </div>
        `;

    } catch (e) {
        body.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <div class="bg-red-100 p-3 rounded-full w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                </div>
                <p class="text-red-600 font-medium">Gagal memuat detail</p>
                <p class="text-red-500 text-sm mt-1">${e.message}</p>
            </div>
        `;
    }
}

function closeModal() {
    document.getElementById('tile-modal').classList.add('hidden');
}