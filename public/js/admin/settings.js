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
        else if (currentTab === "tiles") url = `${BASE_API}/tiles`;
        else url = `${BASE_API}/interventions`;

        const res = await fetch(url, { headers });

        // Handle Error Response
        if (!res.ok) throw new Error(`Gagal mengambil data (${res.status})`);

        const json = await res.json();
        const data = json.data || json;

        if (currentTab === "config") renderConfig(data);
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
                        <span class="text-2xl font-bold text-green-600">${data.maxPlayers || 4}</span>
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
                        <span class="text-2xl font-bold text-green-600">${data.max_turns || 50}</span>
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
    `
    )
        .join("");

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
    `
    )
        .join("");

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
                        <label class="block text-sm font-bold text-gray-700 mb-2">Judul</label>
                        <input type="text" name="title_template" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pesan</label>
                        <textarea name="message_template" rows="3" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tombol (JSON)</label>
                        <textarea name="actions_json" rows="3" placeholder='[{"text": "OK"}]' required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none font-mono text-xs"></textarea>
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
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tombol (JSON)</label>
                            <textarea name="actions_json" rows="3" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none font-mono text-xs">${JSON.stringify(
                data.actions
            )}</textarea>
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
        // Parse JSON actions
        let actions = [];
        try {
            actions = JSON.parse(formData.get("actions_json"));
        } catch {
            showNotification("Format JSON tombol tidak valid!", "error");
            return;
        }

        const payload = {
            level: parseInt(formData.get("level")),
            risk_level: formData.get("risk_level"),
            title_template: formData.get("title_template"),
            message_template: formData.get("message_template"),
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
