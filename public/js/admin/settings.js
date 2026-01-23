const headers = {
    Authorization: `Bearer ${token}`,
    Accept: "application/json",
};
let currentTab = "config";

document.addEventListener("DOMContentLoaded", () => loadData());

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
    const container = document.getElementById("settings-content");
    container.innerHTML = '<div class="loader"></div>';

    try {
        let url;
        if (currentTab === "config") url = `${BASE_API}/config/game`;
        else if (currentTab === "scoring") url = `${BASE_API}/config/scoring`;
        else if (currentTab === "tiles") url = `${BASE_API}/tiles`;
        else url = `${BASE_API}/interventions`;

        const res = await fetch(url, { headers });

        // Handle Error Response
        if (!res.ok) throw new Error(`Gagal mengambil data (${res.status})`);

        const json = await res.json();
        const data = json.data || json;

        if (currentTab === "config") renderConfig(data);
        else if (currentTab === "scoring") renderScoringConfig(data);
        else if (currentTab === "tiles") renderTiles(data);
        else renderInterventions(data);
    } catch (e) {
        container.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-lg">
            <i class="fa-solid fa-exclamation-triangle mr-2"></i>Error: ${e.message}
        </div>`;
    }
}

// --- 1. RENDER CONFIG ---
function renderConfig(data) {
    const container = document.getElementById("settings-content");
    container.innerHTML = `
        <div class="max-w-4xl">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fa-solid fa-cog text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-zinc-800">Aturan Dasar Permainan</h3>
                        <p class="text-sm text-zinc-600">Konfigurasi batasan jumlah pemain dan durasi permainan.</p>
                    </div>
                </div>
            </div>
            
            <form id="configForm" onsubmit="saveConfig(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Min Players -->
                    <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-3">
                            <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                                <i class="fa-solid fa-user-minus text-zinc-600"></i>
                            </div>
                            <label class="block text-zinc-700 font-semibold">Minimal Pemain</label>
                        </div>
                        <input type="number" id="minPlayers" value="${data.minPlayers || 1}" min="1" max="100"
                            class="w-full bg-zinc-50 border border-zinc-300 text-zinc-800 text-lg font-bold rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5">
                        <p class="mt-2 text-xs text-zinc-500">Jumlah minimum pemain untuk memulai game.</p>
                    </div>

                    <!-- Max Players -->
                    <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-3">
                            <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                                <i class="fa-solid fa-users text-zinc-600"></i>
                            </div>
                            <label class="block text-zinc-700 font-semibold">Maksimal Pemain</label>
                        </div>
                        <input type="number" id="maxPlayers" value="${data.maxPlayers || 4}" min="1" max="100"
                            class="w-full bg-zinc-50 border border-zinc-300 text-zinc-800 text-lg font-bold rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5">
                        <p class="mt-2 text-xs text-zinc-500">Batas maksimum pemain dalam satu room.</p>
                    </div>
                    
                    <!-- Max Turns -->
                    <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center mb-3">
                            <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                                <i class="fa-solid fa-clock text-zinc-600"></i>
                            </div>
                            <label class="block text-zinc-700 font-semibold">Batas Giliran (Turns)</label>
                        </div>
                        <input type="number" id="max_turns" value="${data.max_turns || 50}" min="1" max="500"
                            class="w-full bg-zinc-50 border border-zinc-300 text-zinc-800 text-lg font-bold rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5">
                        <p class="mt-2 text-xs text-zinc-500">Maksimal putaran sebelum game berakhir otomatis.</p>
                    </div>

                    <!-- Version Info -->
                    <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
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
                        <p class="mt-4 text-xs text-zinc-500">Versi konfigurasi saat ini.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end border-t border-zinc-200 pt-6">
                    <button type="button" onclick="resetConfigForm()" 
                        class="text-zinc-600 hover:text-zinc-800 font-medium px-6 py-2.5 mr-4 transition-colors">
                        <i class="fa-solid fa-rotate-right mr-2"></i>Reset
                    </button>
                    <button type="submit" 
                        class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-8 py-3 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    `;
}

// --- 1B. RENDER SCORING CONFIG ---
function renderScoringConfig(data) {
    const container = document.getElementById("settings-content");

    // Map config_key to user-friendly labels and icons
    const configLabels = {
        'max_player_score': {
            label: 'Skor Maksimal Pemain',
            icon: 'trophy',
            min: 1,
            max: 1000,
            step: 1
        },
        'sensitivity_factor': {
            label: 'Faktor Sensitivitas',
            icon: 'sliders',
            min: 0,
            max: 1,
            step: 0.01
        },
        'min_score_multiplier': {
            label: 'Multiplier Skor Minimum',
            icon: 'arrow-down',
            min: 0,
            max: 1,
            step: 0.01
        },
        'max_score_multiplier': {
            label: 'Multiplier Skor Maksimum',
            icon: 'arrow-up',
            min: 1,
            max: 10,
            step: 0.1
        }
    };

    // Define order for 2x2 layout (top row, bottom row)
    const orderedKeys = [
        'max_player_score',
        'sensitivity_factor',
        'min_score_multiplier',
        'max_score_multiplier'
    ];

    // Build config cards in specific order
    let configCards = '';
    orderedKeys.forEach(key => {
        const config = data.find(c => c.config_key === key);
        if (!config) return;

        const meta = configLabels[key];
        if (!meta) return;

        // Format value - remove trailing zeros for integers
        let displayValue = config.config_value;
        if (key === 'max_player_score') {
            displayValue = parseInt(config.config_value);
        }

        configCards += `
            <div class="bg-white border border-zinc-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    <div class="bg-zinc-100 p-2 rounded-lg mr-3">
                        <i class="fa-solid fa-${meta.icon} text-zinc-600"></i>
                    </div>
                    <label class="block text-zinc-700 font-semibold">${meta.label}</label>
                </div>
                <input type="number" id="${config.config_key}" 
                    value="${displayValue}" 
                    min="${meta.min}" 
                    max="${meta.max}" 
                    step="${meta.step}"
                    class="w-full bg-zinc-50 border border-zinc-300 text-zinc-800 text-lg font-bold rounded-lg focus:ring-green-500 focus:border-green-500 block p-2.5">
                <p class="mt-2 text-xs text-zinc-500 text-justify">${config.description || ''}</p>
            </div>
        `;
    });

    container.innerHTML = `
        <div class="max-w-6xl">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fa-solid fa-calculator text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-zinc-800">Konfigurasi Skor</h3>
                        <p class="text-sm text-zinc-600">Pengaturan sistem penilaian dan weighted scoring untuk permainan.</p>
                    </div>
                </div>
            </div>
            
            <form id="scoringConfigForm" onsubmit="saveScoringConfig(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    ${configCards}
                </div>

                <div class="flex items-center justify-end border-t border-zinc-200 pt-6">
                    <button type="button" onclick="resetScoringConfigForm()" 
                        class="text-zinc-600 hover:text-zinc-800 font-medium px-6 py-2.5 mr-4 transition-colors">
                        <i class="fa-solid fa-rotate-right mr-2"></i>Reset
                    </button>
                    <button type="submit" 
                        class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-8 py-3 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    `;
}

// Helper to display content info for each tile type
// Helper to display content info for each tile type
function getContentDisplay(tile) {
    // For start/finish tiles, no content needed
    if (tile.type === 'start' || tile.type === 'finish') {
        return '-';
    }

    // 1. Scenario Categories (Prioritize category field)
    if (tile.type === 'scenario' || tile.content_type === 'scenario_category') {
        if (tile.category) return `Kategori: ${tile.category}`;
    }

    // 2. Specific Content (with Title)
    if (tile.content_title) {
        return `<span class="font-semibold block truncate max-w-[200px]" title="${tile.content_title}">${tile.content_title}</span>
                <span class="text-xs text-zinc-400">ID: ${tile.content_id}</span>`;
    }

    // 3. Specific Content (ID only fallback)
    if (tile.content_id) {
        return `ID: ${tile.content_id}`;
    }

    // 4. Random/Properties (matching Seeder logic)
    if (['risk', 'chance', 'quiz', 'property'].includes(tile.type)) {
        if (tile.type === 'property' && tile.content_type) {
            return `Tipe: ${tile.content_type}`;
        }
        return `Acak (${tile.type})`;
    }

    return 'Konten Acak/Kosong';
}

// --- 2. RENDER TILES (PETA) ---
function renderTiles(tiles) {
    const container = document.getElementById('settings-content');

    // Header with Add Button
    const headerHtml = `
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6 flex justify-between items-center mb-6">
            <div>
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
            <button onclick="showAddTileModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow-sm transition-colors flex items-center transform hover:scale-105">
                <i class="fa-solid fa-plus mr-2"></i>Tambah Tile
            </button>
        </div>`;

    if (!tiles.length) {
        container.innerHTML = `
            <div class="space-y-6">
                ${headerHtml}
                <div class="text-center py-12 border-2 border-dashed border-zinc-300 rounded-lg bg-zinc-50">
                    <div class="bg-white p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-map text-zinc-400 text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-zinc-700 mb-2">Belum ada tile</h4>
                    <p class="text-zinc-500 mb-6 max-w-md mx-auto">Tambahkan tile untuk membuat peta papan permainan.</p>
                    <button onclick="showAddTileModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i>Buat Tile Baru
                    </button>
                </div>
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
                    ${getContentDisplay(t)}
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
                <div class="flex gap-2 justify-end">
                    <button onclick="editTile('${t.tile_id}')" 
                        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 hover:text-yellow-800 px-3 py-1.5 rounded-lg font-medium text-sm transition-colors border border-yellow-200 hover:border-yellow-300" title="Edit">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button onclick="showTileDetail('${t.tile_id}')" 
                        class="bg-green-100 hover:bg-green-200 text-green-700 hover:text-green-800 px-3 py-1.5 rounded-lg font-medium text-sm transition-colors border border-green-200 hover:border-green-300" title="Lihat">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <button onclick="deleteTile('${t.tile_id}')" 
                        class="bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 px-3 py-1.5 rounded-lg font-medium text-sm transition-colors border border-red-200 hover:border-red-300" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `
    )
        .join("");

    container.innerHTML = `
        <div class="space-y-6">
            ${headerHtml}
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

    // Header with Add Button
    const headerHtml = `
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6 flex justify-between items-center mb-6">
                <div>
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
                <button onclick="showAddInterventionModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow-sm transition-colors flex items-center transform hover:scale-105">
                    <i class="fa-solid fa-plus mr-2"></i>Tambah Template
                </button>
            </div>`;

    if (!items.length) {
        container.innerHTML = `
            <div class="space-y-6">
                ${headerHtml}
                <div class="text-center py-12 border-2 border-dashed border-zinc-300 rounded-lg bg-zinc-50">
                    <div class="bg-white p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-robot text-zinc-400 text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-zinc-700 mb-2">Belum ada template intervensi</h4>
                    <p class="text-zinc-500 mb-6 max-w-md mx-auto">Tambahkan template intervensi untuk memberikan pesan otomatis dari AI kepada pemain.</p>
                    <button onclick="showAddInterventionModal()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i>Buat Template Baru
                    </button>
                </div>
            </div>
        `;
        return;
    }

    let cards = items.map(i => `
        <div class="bg-white border border-zinc-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden relative group">
            <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-10 bg-white p-1 rounded-lg shadow-sm border border-zinc-100">
                <button onclick="editIntervention('${i.id}')" class="text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg transition-colors border border-transparent hover:border-yellow-200" title="Edit">
                    <i class="fa-solid fa-edit"></i>
                </button>
                <button onclick="deleteIntervention('${i.id}')" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors border border-transparent hover:border-red-200" title="Hapus">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
            <div class="border-l-4 ${i.ui_color === 'red' ? 'border-red-500' : (i.ui_color === 'orange' ? 'border-orange-500' : 'border-yellow-500')} p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="bg-zinc-100 text-zinc-600 text-xs font-semibold px-2 py-1 rounded-full mr-3 border border-zinc-200">
                                Level ${i.level_id}
                            </span>
                            <span class="bg-${i.ui_color === 'red' ? 'red' : (i.ui_color === 'orange' ? 'orange' : 'yellow')}-100 text-${i.ui_color === 'red' ? 'red' : (i.ui_color === 'orange' ? 'orange' : 'yellow')}-700 text-xs font-semibold px-2 py-1 rounded-full border border-${i.ui_color === 'red' ? 'red' : (i.ui_color === 'orange' ? 'orange' : 'yellow')}-200">
                                ${i.risk_label}
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-zinc-800 mb-3">${i.title}</h4>
                        <div class="bg-zinc-50 border border-zinc-200 rounded-lg p-4 relative">
                            <i class="fa-solid fa-quote-left text-zinc-300 absolute top-2 left-2 text-xl"></i>
                            <p class="text-zinc-700 italic leading-relaxed pl-6 relative z-10">"${i.message}"</p>
                        </div>
                    </div>
                    ${i.is_mandatory ? `
                        <div class="ml-4">
                            <span class="bg-red-100 border border-red-200 text-red-800 text-xs px-3 py-1 rounded-full font-semibold flex items-center">
                                <i class="fa-solid fa-lock mr-1.5"></i>WAJIB
                            </span>
                        </div>
                    ` : ''}
                </div>
                
                <div class="pt-4 border-t border-zinc-100">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="bg-green-100 p-1.5 rounded-lg">
                            <i class="fa-solid fa-mouse-pointer text-green-600 text-xs"></i>
                        </div>
                        <span class="text-sm font-semibold text-zinc-700">Tombol Aksi:</span>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        ${i.actions ? i.actions.map(a => `
                            <span class="bg-white border border-green-200 text-green-700 text-sm px-3 py-1.5 rounded-lg font-medium shadow-sm flex items-center">
                                ${a.text}
                            </span>
                        `).join('') : '<span class="text-zinc-400 text-sm italic">Tidak ada aksi</span>'}
                    </div>
                </div>
            </div>
        </div>
    `
    )
        .join("");

    container.innerHTML = `
        <div class="space-y-6">
            ${headerHtml}
            <div class="grid grid-cols-1 gap-6">
                ${cards}
            </div>
        </div>
    `;
}

// --- 4. LOGIC MODAL TILE DETAIL ---
async function showTileDetail(id) {
    const modal = document.getElementById("tile-modal");
    const body = document.getElementById("modal-body");

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
                        <div>
                            ${(() => {
                if (t.type === 'start' || t.type === 'finish') {
                    return '<span class="text-zinc-400 italic text-sm">Tidak memerlukan konten</span>';
                }
                // Scenario Category
                if (t.category || t.content_type === 'scenario_category') {
                    return `
                                        <p class="font-bold text-green-700 mb-1">Kategori: ${t.category || 'Unknown'}</p>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded border border-green-200">
                                                Scenario Pool
                                            </span>
                                        </div>`;
                }
                // Specific Content
                if (t.content_title) {
                    return `
                                        <p class="font-bold text-green-700 mb-1 line-clamp-2" title="${t.content_title}">${t.content_title}</p>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs bg-zinc-100 text-zinc-600 px-2 py-1 rounded border border-zinc-200 font-mono">
                                                ID: ${t.content_id}
                                            </span>
                                            <span class="text-xs text-zinc-400 ml-2 capitalize">(${t.content_type})</span>
                                        </div>`;
                }
                // Random / Property Type
                if (['risk', 'chance', 'quiz', 'property'].includes(t.type)) {
                    if (t.type === 'property' && t.content_type) {
                        return `<p class="font-bold text-blue-600">Tipe: ${t.content_type}</p>`;
                    }
                    return `<p class="font-bold text-purple-600">Acak (${t.type})</p>`;
                }
                return '<span class="text-zinc-400 italic text-sm">Tidak ada konten tertaut</span>';
            })()}
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fa-solid fa-code text-blue-600"></i>
                        </div>
                        <p class="text-sm font-semibold text-blue-700 uppercase tracking-wide">
                            Linked Content (JSON)
                        </p>
                    </div>
                    <pre class="bg-white border border-blue-200 rounded p-3 text-xs font-mono overflow-x-auto max-h-32">${t.linked_content_raw ? JSON.stringify(JSON.parse(t.linked_content_raw), null, 2) : '<span class="text-gray-400 italic">Tidak ada linked content</span>'}</pre>
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
    document.getElementById("tile-modal").classList.add("hidden");
}

// --- SAVE CONFIG ---
async function saveConfig(e) {
    e.preventDefault();

    const form = document.getElementById("configForm");
    const minPlayers = parseInt(document.getElementById("minPlayers").value);
    const maxPlayers = parseInt(document.getElementById("maxPlayers").value);
    const max_turns = parseInt(document.getElementById("max_turns").value);

    // Validasi
    if (!minPlayers || !maxPlayers || !max_turns) {
        alert("Semua field harus diisi!");
        return;
    }

    if (minPlayers < 1 || minPlayers > 100) {
        alert("Minimal pemain harus antara 1-100!");
        return;
    }

    if (maxPlayers < 1 || maxPlayers > 100) {
        alert("Maksimal pemain harus antara 1-100!");
        return;
    }

    if (minPlayers > maxPlayers) {
        alert("Minimal pemain tidak boleh lebih dari maksimal pemain!");
        return;
    }

    if (max_turns < 1 || max_turns > 500) {
        alert("Batas giliran harus antara 1-500!");
        return;
    }

    try {
        const response = await fetch(`${BASE_API}/config/game`, {
            method: "PUT",
            headers: {
                ...headers,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                minPlayers,
                maxPlayers,
                max_turns,
            }),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showNotification("Konfigurasi berhasil diperbarui!", "success");
            setTimeout(() => loadData(), 1500);
        } else {
            showNotification(
                result.message || "Gagal menyimpan konfigurasi",
                "error"
            );
        }
    } catch (e) {
        showNotification(`Error: ${e.message}`, "error");
    }
}

// --- RESET CONFIG FORM ---
function resetConfigForm() {
    loadData();
}

// --- SAVE SCORING CONFIG ---
async function saveScoringConfig(e) {
    e.preventDefault();

    // Collect all config values
    const configs = [
        {
            config_key: 'max_player_score',
            config_value: parseFloat(document.getElementById('max_player_score').value)
        },
        {
            config_key: 'sensitivity_factor',
            config_value: parseFloat(document.getElementById('sensitivity_factor').value)
        },
        {
            config_key: 'min_score_multiplier',
            config_value: parseFloat(document.getElementById('min_score_multiplier').value)
        },
        {
            config_key: 'max_score_multiplier',
            config_value: parseFloat(document.getElementById('max_score_multiplier').value)
        }
    ];

    // Validasi
    for (const config of configs) {
        if (isNaN(config.config_value)) {
            alert(`Nilai untuk ${config.config_key} harus berupa angka!`);
            return;
        }
    }

    // Validasi khusus per config
    const maxPlayerScore = configs.find(c => c.config_key === 'max_player_score').config_value;
    if (maxPlayerScore <= 0 || maxPlayerScore > 1000) {
        alert('Skor Maksimal Pemain harus antara 1-1000!');
        return;
    }

    const sensitivityFactor = configs.find(c => c.config_key === 'sensitivity_factor').config_value;
    if (sensitivityFactor < 0 || sensitivityFactor > 1) {
        alert('Faktor Sensitivitas harus antara 0-1!');
        return;
    }

    const minMultiplier = configs.find(c => c.config_key === 'min_score_multiplier').config_value;
    if (minMultiplier < 0 || minMultiplier > 1) {
        alert('Multiplier Skor Minimum harus antara 0-1!');
        return;
    }

    const maxMultiplier = configs.find(c => c.config_key === 'max_score_multiplier').config_value;
    if (maxMultiplier < 1 || maxMultiplier > 10) {
        alert('Multiplier Skor Maksimum harus antara 1-10!');
        return;
    }

    try {
        const response = await fetch(`${BASE_API}/config/scoring`, {
            method: 'PUT',
            headers: {
                ...headers,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ configs }),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showNotification('Konfigurasi skor berhasil diperbarui!', 'success');
            setTimeout(() => loadData(), 1500);
        } else {
            showNotification(
                result.message || 'Gagal menyimpan konfigurasi skor',
                'error'
            );
        }
    } catch (e) {
        showNotification(`Error: ${e.message}`, 'error');
    }
}

// --- RESET SCORING CONFIG FORM ---
function resetScoringConfigForm() {
    loadData();
}

// --- NOTIFICATION HELPER ---
function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium transition z-40 ${type === "success"
        ? "bg-green-500"
        : type === "error"
            ? "bg-red-500"
            : "bg-blue-500"
        }`;
    notification.innerHTML = `
        ${type === "success"
            ? '<i class="fa-solid fa-check-circle mr-2"></i>'
            : ""
        }
        ${type === "error"
            ? '<i class="fa-solid fa-exclamation-circle mr-2"></i>'
            : ""
        }
        ${message}
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// --- INTERVENTION CRUD ---
let interventionModal = null;
let currentInterventionId = null;

function showAddInterventionModal() {
    currentInterventionId = null;
    const modal = document.createElement("div");
    modal.className =
        "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";
    modal.id = "intervention-modal";
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-96 max-h-[90vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Tambah Template Intervensi</h3>
                <button onclick="closeInterventionModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="interventionForm" onsubmit="saveIntervention(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Level</label>
                        <input type="number" name="level" min="1" max="10" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Risiko</label>
                        <select name="risk_level" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                            <option value="Critical">Critical</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                        <select name="category" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="pendapatan">Pendapatan</option>
                            <option value="anggaran">Anggaran</option>
                            <option value="tabungan_dan_dana_darurat">Tabungan dan Dana Darurat</option>
                            <option value="utang">Utang</option>
                            <option value="investasi">Investasi</option>
                            <option value="asuransi">Asuransi</option>
                            <option value="tujuan_jangka_panjang">Tujuan Jangka Panjang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Judul</label>
                        <input type="text" name="title_template" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pesan</label>
                        <textarea name="message_template" rows="3" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hint Message</label>
                        <textarea name="heed_message" rows="2" placeholder="Pesan hint yang muncul jika intervensi..." class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tombol Aksi</label>
                        <div class="space-y-3 mb-2">
                            <div class="border border-gray-300 rounded p-3 bg-gray-50">
                                <p class="text-xs font-semibold text-gray-600 mb-2">Tombol 1</p>
                                <input type="text" name="action_id_0" value="heeded" placeholder="ID tombol" class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none mb-2 text-sm">
                                <input type="text" name="action_text_0" value="Lihat Penjelasan Lengkap" placeholder="Teks tombol" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none text-sm">
                            </div>
                            <div class="border border-gray-300 rounded p-3 bg-gray-50">
                                <p class="text-xs font-semibold text-gray-600 mb-2">Tombol 2</p>
                                <input type="text" name="action_id_1" value="ignored" placeholder="ID tombol" class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none mb-2 text-sm">
                                <input type="text" name="action_text_1" value="Saya Sudah Yakin, Lanjut" placeholder="Teks tombol" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none text-sm">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">Tombol pertama untuk 'heeded' (melihat penjelasan), tombol kedua untuk 'ignored' (melewati)</p>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_mandatory" id="is_mandatory" class="rounded">
                        <label for="is_mandatory" class="ml-2 text-sm font-bold text-gray-700">Wajib (Mandatory)</label>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeInterventionModal()" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                        <i class="fa-solid fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    interventionModal = modal;
}

async function editIntervention(id) {
    try {
        const res = await fetch(`${BASE_API}/interventions/${id}`, { headers });
        const json = await res.json();
        const data = json.data;

        currentInterventionId = id;
        const modal = document.createElement("div");
        modal.className =
            "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";
        modal.id = "intervention-modal";
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg w-96 max-h-[90vh] overflow-y-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Edit Template Intervensi</h3>
                    <button onclick="closeInterventionModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form id="interventionForm" onsubmit="saveIntervention(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Level</label>
                            <input type="number" name="level" value="${data.level_id
            }" min="1" max="10" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Risiko</label>
                            <select name="risk_level" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                                <option value="Critical" ${data.risk_label === "Critical"
                ? "selected"
                : ""
            }>Critical</option>
                                <option value="High" ${data.risk_label === "High" ? "selected" : ""
            }>High</option>
                                <option value="Medium" ${data.risk_label === "Medium"
                ? "selected"
                : ""
            }>Medium</option>
                                <option value="Low" ${data.risk_label === "Low" ? "selected" : ""
            }>Low</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                            <select name="category" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="pendapatan" ${data.category === "pendapatan" ? "selected" : ""}>Pendapatan</option>
                                <option value="anggaran" ${data.category === "anggaran" ? "selected" : ""}>Anggaran</option>
                                <option value="tabungan_dan_dana_darurat" ${data.category === "tabungan_dan_dana_darurat" ? "selected" : ""}>Tabungan dan Dana Darurat</option>
                                <option value="utang" ${data.category === "utang" ? "selected" : ""}>Utang</option>
                                <option value="investasi" ${data.category === "investasi" ? "selected" : ""}>Investasi</option>
                                <option value="asuransi" ${data.category === "asuransi" ? "selected" : ""}>Asuransi</option>
                                <option value="tujuan_jangka_panjang" ${data.category === "tujuan_jangka_panjang" ? "selected" : ""}>Tujuan Jangka Panjang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul</label>
                            <input type="text" name="title_template" value="${data.title
            }" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pesan</label>
                            <textarea name="message_template" rows="3" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">${data.message
            }</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Heed Message (Pesan Jika Diabaikan)</label>
                            <textarea name="heed_message" rows="2" placeholder="Pesan yang muncul jika intervensi diabaikan..." class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">${data.heed_message || ''}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tombol Aksi</label>
                            <div class="space-y-3 mb-2">
                                ${data.actions && data.actions.length >= 1 ? `
                                <div class="border border-gray-300 rounded p-3 bg-gray-50">
                                    <p class="text-xs font-semibold text-gray-600 mb-2">Tombol 1</p>
                                    <input type="text" name="action_id_0" value="${data.actions[0].id || 'heeded'}" placeholder="ID tombol" class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none mb-2 text-sm">
                                    <input type="text" name="action_text_0" value="${data.actions[0].text || 'Lihat Penjelasan Lengkap'}" placeholder="Teks tombol" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none text-sm">
                                </div>
                                ` : ''}
                                ${data.actions && data.actions.length >= 2 ? `
                                <div class="border border-gray-300 rounded p-3 bg-gray-50">
                                    <p class="text-xs font-semibold text-gray-600 mb-2">Tombol 2</p>
                                    <input type="text" name="action_id_1" value="${data.actions[1].id || 'ignored'}" placeholder="ID tombol" class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none mb-2 text-sm">
                                    <input type="text" name="action_text_1" value="${data.actions[1].text || 'Saya Sudah Yakin, Lanjut'}" placeholder="Teks tombol" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none text-sm">
                                </div>
                                ` : ''}
                            </div>
                            <p class="text-xs text-gray-500">Tombol pertama untuk 'heeded' (melihat penjelasan), tombol kedua untuk 'ignored' (melewati)</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_mandatory" id="is_mandatory" ${data.is_mandatory ? "checked" : ""
            } class="rounded">
                            <label for="is_mandatory" class="ml-2 text-sm font-bold text-gray-700">Wajib (Mandatory)</label>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeInterventionModal()" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                            <i class="fa-solid fa-save mr-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        `;
        if (interventionModal) interventionModal.remove();
        document.body.appendChild(modal);
        interventionModal = modal;
    } catch (e) {
        showNotification(`Error: ${e.message}`, "error");
    }
}

function closeInterventionModal() {
    if (interventionModal) {
        interventionModal.remove();
        interventionModal = null;
    }
}

async function saveIntervention(e) {
    e.preventDefault();
    const form = document.getElementById("interventionForm");
    const formData = new FormData(form);

    try {
        // Build actions array from form fields
        let actions = [];
        let i = 0;
        while (formData.has(`action_text_${i}`)) {
            const actionId = formData.get(`action_id_${i}`) || `action_${i}`;
            const actionText = formData.get(`action_text_${i}`);
            if (actionText) {
                actions.push({
                    id: actionId,
                    text: actionText
                });
            }
            i++;
        }

        if (actions.length === 0) {
            showNotification("Minimal harus ada 1 tombol aksi!", "error");
            return;
        }

        const payload = {
            level: parseInt(formData.get("level")),
            risk_level: formData.get("risk_level"),
            category: formData.get("category"),
            title_template: formData.get("title_template"),
            message_template: formData.get("message_template"),
            heed_message: formData.get("heed_message") || null,
            actions: actions,
            is_mandatory: formData.get("is_mandatory") ? true : false,
        };

        const method = currentInterventionId ? "PUT" : "POST";
        const url = currentInterventionId
            ? `${BASE_API}/interventions/${currentInterventionId}`
            : `${BASE_API}/interventions`;

        const response = await fetch(url, {
            method: method,
            headers: {
                ...headers,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showNotification(result.message, "success");
            closeInterventionModal();
            setTimeout(() => loadData(), 1500);
        } else {
            showNotification(result.message || "Gagal menyimpan", "error");
        }
    } catch (e) {
        showNotification(`Error: ${e.message}`, "error");
    }
}

async function deleteIntervention(id) {
    if (!confirm("Apakah Anda yakin ingin menghapus template ini?")) return;

    try {
        const response = await fetch(`${BASE_API}/interventions/${id}`, {
            method: "DELETE",
            headers,
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showNotification(result.message, "success");
            setTimeout(() => loadData(), 1500);
        } else {
            showNotification(result.message || "Gagal menghapus", "error");
        }
    } catch (e) {
        showNotification(`Error: ${e.message}`, "error");
    }
}

// --- TILE CRUD ---
let tileModal = null;
let currentTileId = null;

// Render dynamic fields based on tile type
function renderDynamicFields(tileType, data = {}) {
    const container = document.getElementById('dynamic-fields-container');
    if (!container) return;

    // Parse existing linked_content if available
    let linkedContent = {};
    if (data.linked_content_raw) {
        try { linkedContent = JSON.parse(data.linked_content_raw); } catch (e) { }
    }

    let html = '';

    switch (tileType) {
        case 'scenario':
            const categoryMapping = {
                'pendapatan': ['Uang Bulanan', 'Pendapatan', 'Beasiswa'],
                'anggaran': ['Makan', 'Transport', 'Nongkrong'],
                'tabungan_dan_dana_darurat': ['Tabungan', 'Dana Darurat', 'Deposito'],
                'utang': ['Pinjaman Teman', 'Pinjol', 'Utang'],
                'investasi': ['Reksadana', 'Saham', 'Cryptocurrency'],
                'asuransi_dan_proteksi': ['Asuransi Kesehatan', 'Asuransi Kendaraan', 'Asuransi Barang/Harta'],
                'tujuan_jangka_panjang': ['Pendidikan', 'Pengalaman', 'Aset Produktif']
            };
            
            const scenarioCategory = linkedContent.category || data.category || '';
            const scenarioName = data.name || '';
            
            html = `
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="scenario-category" required onchange="updateScenarioNames()" class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="pendapatan" ${scenarioCategory === 'pendapatan' ? 'selected' : ''}>Pendapatan</option>
                        <option value="anggaran" ${scenarioCategory === 'anggaran' ? 'selected' : ''}>Anggaran</option>
                        <option value="tabungan_dan_dana_darurat" ${scenarioCategory === 'tabungan_dan_dana_darurat' ? 'selected' : ''}>Tabungan dan Dana Darurat</option>
                        <option value="utang" ${scenarioCategory === 'utang' ? 'selected' : ''}>Utang</option>
                        <option value="investasi" ${scenarioCategory === 'investasi' ? 'selected' : ''}>Investasi</option>
                        <option value="asuransi_dan_proteksi" ${scenarioCategory === 'asuransi_dan_proteksi' ? 'selected' : ''}>Asuransi dan Proteksi</option>
                        <option value="tujuan_jangka_panjang" ${scenarioCategory === 'tujuan_jangka_panjang' ? 'selected' : ''}>Tujuan Jangka Panjang</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih kategori untuk tile scenario</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Tile</label>
                    <select name="scenario_name" id="scenario-name" required class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none">
                        <option value="">-- Pilih Kategori Terlebih Dahulu --</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Nama tile akan muncul setelah memilih kategori</p>
                </div>
            `;
            
            // Store category mapping globally for updateScenarioNames function
            window.categoryMapping = categoryMapping;
            window.initialScenarioName = scenarioName;
            break;

        case 'property':
            const propertyType = linkedContent.type || '';
            const riskLevel = linkedContent.risk_level || '';
            const interestRate = linkedContent.interest_rate || '';
            const coverage = linkedContent.coverage || '';
            const generatesIncome = linkedContent.generates_income || '';
            const termMonths = linkedContent.term || '';
            html = `
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Aset</label>
                    <input list="property-list" name="property_type" value="${propertyType}"
                        class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                        placeholder="Pilih atau ketik tipe aset...">
                    <datalist id="property-list">
                        <option value="investment">Investasi (Saham/Reksadana)</option>
                        <option value="savings">Tabungan & Deposito</option>
                        <option value="insurance">Asuransi (Proteksi)</option>
                        <option value="asset">Aset Produktif</option>
                        <option value="education_investment">Investasi Pendidikan</option>
                    </datalist>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Level Risiko</label>
                        <input list="risk-level-list" name="risk_level" value="${riskLevel}"
                            class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                            placeholder="low/medium/high">
                        <datalist id="risk-level-list">
                            <option value="low">Rendah</option>
                            <option value="medium">Menengah</option>
                            <option value="high">Tinggi</option>
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Interest Rate (%)</label>
                        <input type="number" step="0.1" name="interest_rate" value="${interestRate}"
                            class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                            placeholder="contoh: 2.5">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Coverage</label>
                        <input list="coverage-list" name="coverage" value="${coverage}"
                            class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                            placeholder="Jenis coverage...">
                        <datalist id="coverage-list">
                            <option value="property">Property</option>
                            <option value="health">Health</option>
                            <option value="life">Life</option>
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Term (bulan)</label>
                        <input type="number" name="term" value="${termMonths}"
                            class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                            placeholder="contoh: 12">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Generates Income</label>
                    <select name="generates_income" class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none">
                        <option value="">-- Tidak diset --</option>
                        <option value="true" ${generatesIncome === true ? 'selected' : ''}>Ya (true)</option>
                        <option value="false" ${generatesIncome === false ? 'selected' : ''}>Tidak (false)</option>
                    </select>
                </div>
            `;
            break;

        case 'risk':
            const riskCardId = linkedContent.card_id || '';
            const riskSeverity = linkedContent.severity || '';
            html = `
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kartu Risk</label>
                    <input list="risk-cards-list" name="card_id" value="${riskCardId}"
                        class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                        placeholder="Kosongkan untuk acak atau pilih/ketik ID...">
                    <datalist id="risk-cards-list">
                        ${availableContents?.risks?.map(r =>
                `<option value="${r.id}">${r.title}</option>`
            ).join('') || ''}
                    </datalist>
                    <p class="text-xs text-gray-500 mt-1">Pilih kartu spesifik atau kosongkan untuk acak.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Severity</label>
                    <input list="severity-list" name="severity" value="${riskSeverity}"
                        class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                        placeholder="Tingkat keparahan risiko...">
                    <datalist id="severity-list">
                        <option value="low">Rendah</option>
                        <option value="medium">Menengah</option>
                        <option value="high">Tinggi</option>
                    </datalist>
                </div>
            `;
            break;

        case 'chance':
            const chanceCardId = linkedContent.card_id || '';
            const incomeType = linkedContent.income_type || '';
            const chanceBenefit = linkedContent.benefit || '';
            html = `
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kartu Chance</label>
                    <input list="chance-cards-list" name="card_id" value="${chanceCardId}"
                        class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                        placeholder="Kosongkan untuk acak atau pilih/ketik ID...">
                    <datalist id="chance-cards-list">
                        ${availableContents?.chances?.map(c =>
                `<option value="${c.id}">${c.title}</option>`
            ).join('') || ''}
                    </datalist>
                    <p class="text-xs text-gray-500 mt-1">Pilih kartu spesifik atau kosongkan untuk acak.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Income Type</label>
                        <input list="income-type-list" name="income_type" value="${incomeType}"
                            class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                            placeholder="Jenis pendapatan...">
                        <datalist id="income-type-list">
                            <option value="side_hustle">Side Hustle</option>
                            <option value="bonus">Bonus</option>
                            <option value="gift">Gift</option>
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Benefit</label>
                        <input list="benefit-list" name="benefit" value="${chanceBenefit}"
                            class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                            placeholder="Jenis benefit...">
                        <datalist id="benefit-list">
                            <option value="education">Education</option>
                            <option value="health">Health</option>
                            <option value="financial">Financial</option>
                        </datalist>
                    </div>
                </div>
            `;
            break;

        case 'quiz':
            const quizId = linkedContent.quiz_id || '';
            html = `
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kartu Quiz</label>
                    <input list="quiz-cards-list" name="quiz_id" value="${quizId}"
                        class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none"
                        placeholder="Kosongkan untuk acak atau pilih/ketik ID...">
                    <datalist id="quiz-cards-list">
                        ${availableContents?.quizzes?.map(q =>
                `<option value="${q.id}">${q.title}</option>`
            ).join('') || ''}
                    </datalist>
                    <p class="text-xs text-gray-500 mt-1">Pilih quiz spesifik atau kosongkan untuk acak.</p>
                </div>
            `;
            break;

        case 'start':
        case 'finish':
            html = `
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <i class="fa-solid fa-info-circle text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-500 text-sm">Tile ${tileType.toUpperCase()} tidak memerlukan konfigurasi tambahan.</p>
                </div>
            `;
            break;

        default:
            html = '<p class="text-gray-500 text-sm italic">Pilih tipe tile terlebih dahulu.</p>';
    }

    container.innerHTML = html;
    
    // If scenario type, trigger name dropdown update after rendering
    if (tileType === 'scenario') {
        setTimeout(() => {
            updateScenarioNames();
        }, 0);
    }
}

// Helper function to update scenario names based on selected category
window.updateScenarioNames = function() {
    const categorySelect = document.getElementById('scenario-category');
    const nameSelect = document.getElementById('scenario-name');
    
    if (!categorySelect || !nameSelect) return;
    
    const selectedCategory = categorySelect.value;
    const categoryMapping = window.categoryMapping || {};
    
    // Clear existing options
    nameSelect.innerHTML = '<option value="">-- Pilih Nama --</option>';
    
    // Populate with names for selected category
    if (selectedCategory && categoryMapping[selectedCategory]) {
        categoryMapping[selectedCategory].forEach(name => {
            const option = document.createElement('option');
            option.value = name;
            option.textContent = name;
            
            // Select if this was the initial value (for edit mode)
            if (window.initialScenarioName === name) {
                option.selected = true;
            }
            
            nameSelect.appendChild(option);
        });
    }
    
    // Clear initial value after first use
    window.initialScenarioName = null;
}

async function showAddTileModal() {
    currentTileId = null;

    // Get existing positions and max tile_id
    let existingPositions = [];
    let maxTileId = 0;
    try {
        const res = await fetch(`${BASE_API}/tiles`, { headers });
        const json = await res.json();
        const tiles = json.data || json;
        existingPositions = tiles.map(t => t.position);
        
        // Find max tile_id for sequential numbering
        tiles.forEach(t => {
            const tileIdNum = parseInt(t.tile_id);
            if (!isNaN(tileIdNum) && tileIdNum > maxTileId) {
                maxTileId = tileIdNum;
            }
        });
    } catch (e) {
        console.error('Failed to load existing tiles:', e);
    }

    const modal = document.createElement("div");
    modal.className = "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";
    modal.id = "tile-crud-modal";
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-96 max-h-[90vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Tambah Tile Baru</h3>
                <button onclick="closeTileModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="tileForm" onsubmit="saveTile(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Tile</label>
                        <select name="type" required class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none" onchange="renderDynamicFields(this.value)">
                            <option value="scenario">Scenario</option>
                            <option value="risk">Risk</option>
                            <option value="chance">Chance</option>
                            <option value="quiz">Quiz</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Posisi</label>
                        <input type="number" id="tile-position" name="position" min="0" required class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none" placeholder="0, 1, 2, ...">
                        ${existingPositions.length > 0 ? `
                            <p class="text-xs text-orange-600 mt-1">
                                <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                Posisi yang sudah ada: ${existingPositions.sort((a,b) => a-b).join(', ')}
                            </p>
                        ` : ''}
                        <p class="text-xs text-gray-500 mt-1">Pilih posisi yang belum terpakai</p>
                    </div>
                    <div id="dynamic-fields-container" class="space-y-4">
                        <!-- Dynamic fields will be rendered here -->
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeTileModal()" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                        <i class="fa-solid fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    tileModal = modal;

    // Store existing positions and max tile_id for validation
    window.existingTilePositions = existingPositions;
    window.maxTileId = maxTileId;

    // Initialize dynamic fields for default type (scenario)
    renderDynamicFields('scenario');
}

async function editTile(id) {
    try {
        const res = await fetch(`${BASE_API}/tiles/${id}`, { headers });
        const json = await res.json();
        const data = json.data || json;

        currentTileId = id;

        // Get current tile position from list
        const tilesRes = await fetch(`${BASE_API}/tiles`, { headers });
        const tilesJson = await tilesRes.json();
        const tilesData = tilesJson.data || tilesJson;
        const currentTile = tilesData.find(t => t.tile_id === id);

        // Store data globally for renderDynamicFields to access
        window.currentEditTileData = data;

        const modal = document.createElement("div");
        modal.className = "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";
        modal.id = "tile-crud-modal";
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg w-96 max-h-[90vh] overflow-y-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Edit Tile</h3>
                    <button onclick="closeTileModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <form id="tileForm" onsubmit="saveTile(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Tile</label>
                            <select name="type" required class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none" onchange="renderDynamicFields(this.value, window.currentEditTileData)">
                                <option value="scenario" ${data.type === 'scenario' ? 'selected' : ''}>Scenario</option>
                                <option value="risk" ${data.type === 'risk' ? 'selected' : ''}>Risk</option>
                                <option value="chance" ${data.type === 'chance' ? 'selected' : ''}>Chance</option>
                                <option value="quiz" ${data.type === 'quiz' ? 'selected' : ''}>Quiz</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Posisi</label>
                            <input type="number" name="position" value="${currentTile?.position || 0}" min="0" required class="w-full p-2 border border-gray-300 rounded focus:border-green-500 focus:outline-none" readonly>
                            <p class="text-xs text-gray-500 mt-1">Posisi tidak dapat diubah saat edit</p>
                        </div>
                        <div id="dynamic-fields-container" class="space-y-4">
                            <!-- Dynamic fields will be rendered here -->
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeTileModal()" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                            <i class="fa-solid fa-save mr-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        `;
        if (tileModal) tileModal.remove();
        document.body.appendChild(modal);
        tileModal = modal;

        // Initialize dynamic fields with existing data
        renderDynamicFields(data.type, data);
    } catch (e) {
        showNotification(`Error: ${e.message}`, "error");
    }
}

function closeTileModal() {
    if (tileModal) {
        tileModal.remove();
        tileModal = null;
    }
}

async function saveTile(e) {
    e.preventDefault();
    const form = document.getElementById("tileForm");
    const formData = new FormData(form);

    const tileType = formData.get("type");
    const position = parseInt(formData.get("position"));

    // Validate position is not already taken (only for new tiles)
    if (!currentTileId && window.existingTilePositions && window.existingTilePositions.includes(position)) {
        alert(`Posisi ${position} sudah digunakan! Pilih posisi lain.`);
        return;
    }

    // Build linked_content, name, category, and tile_id based on tile type
    let linkedContent = null;
    let tileName = null;
    let tileCategory = null;
    let tileId = null;

    // Generate tile_id for new tiles (sequential numbering)
    if (!currentTileId) {
        const nextTileId = (window.maxTileId || 0) + 1;
        tileId = nextTileId.toString();
    }

    switch (tileType) {
        case 'scenario':
            const category = formData.get('category');
            const scenarioName = formData.get('scenario_name');
            
            if (!category || !scenarioName) {
                alert('Kategori dan Nama harus diisi untuk tile scenario!');
                return;
            }
            
            linkedContent = {
                type: 'scenario_category',
                category: category
            };
            tileName = scenarioName;
            tileCategory = category;
            break;

        case 'risk':
            linkedContent = {
                card_type: 'risk'
            };
            tileName = 'Risiko';
            tileCategory = 'card';
            break;

        case 'chance':
            linkedContent = {
                card_type: 'chance'
            };
            tileName = 'Kesempatan';
            tileCategory = 'card';
            break;

        case 'quiz':
            linkedContent = {
                card_type: 'quiz'
            };
            tileName = 'Kuis Literasi';
            tileCategory = 'quiz';
            break;

        default:
            alert('Tipe tile tidak valid!');
            return;
    }

    const payload = {
        name: tileName,
        type: tileType,
        position: position,
        category: tileCategory,
        linked_content: linkedContent
    };

    // Add tile_id for new tiles
    if (tileId) {
        payload.tile_id = tileId;
    }

    try {
        const method = currentTileId ? "PUT" : "POST";
        const url = currentTileId
            ? `${BASE_API}/tiles/${currentTileId}`
            : `${BASE_API}/tiles`;

        const response = await fetch(url, {
            method: method,
            headers: {
                ...headers,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showNotification(result.message, "success");
            closeTileModal();
            setTimeout(() => loadData(), 1500);
        } else {
            showNotification(result.message || "Gagal menyimpan tile", "error");
        }
    } catch (e) {
        showNotification(`Error: ${e.message}`, "error");
    }
}

async function deleteTile(id) {
    if (!confirm("Apakah Anda yakin ingin menghapus tile ini?")) return;

    try {
        const response = await fetch(`${BASE_API}/tiles/${id}`, {
            method: "DELETE",
            headers,
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showNotification(result.message, "success");
            setTimeout(() => loadData(), 1500);
        } else {
            showNotification(result.message || "Gagal menghapus tile", "error");
        }
    } catch (e) {
        showNotification(`Error: ${e.message}`, "error");
    }
}
