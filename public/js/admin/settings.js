const headers = {
    Authorization: `Bearer ${token}`,
    Accept: "application/json",
};
let currentTab = "config";

document.addEventListener("DOMContentLoaded", () => loadData());

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.classList.remove("border-indigo-500", "text-indigo-600");
        btn.classList.add("border-transparent", "text-gray-500");
    });
    document
        .getElementById(`tab-${tab}`)
        .classList.remove("border-transparent", "text-gray-500");
    document
        .getElementById(`tab-${tab}`)
        .classList.add("border-indigo-500", "text-indigo-600");

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
        container.innerHTML = `<div class="text-red-500 p-4">Error: ${e.message}</div>`;
    }
}

// --- 1. RENDER CONFIG ---
function renderConfig(data) {
    const container = document.getElementById("settings-content");
    container.innerHTML = `
        <div class="max-w-lg">
            <h3 class="text-lg font-bold mb-4 text-gray-700">Aturan Dasar Permainan</h3>
            <form id="configForm" class="grid grid-cols-1 gap-4">
                <div class="bg-white p-4 rounded border-2 border-indigo-200">
                    <label class="block text-gray-600 text-sm font-bold mb-2">
                        <i class="fa-solid fa-user text-indigo-600 mr-1"></i> Minimal Pemain
                    </label>
                    <input type="number" id="minPlayers" name="minPlayers" value="${
                        data.minPlayers || 2
                    }" class="w-full p-2 rounded border border-gray-300 focus:border-indigo-500 focus:outline-none" min="1" max="100" required>
                </div>
                <div class="bg-white p-4 rounded border-2 border-indigo-200">
                    <label class="block text-gray-600 text-sm font-bold mb-2">
                        <i class="fa-solid fa-users text-indigo-600 mr-1"></i> Maksimal Pemain
                    </label>
                    <input type="number" id="maxPlayers" name="maxPlayers" value="${
                        data.maxPlayers || 4
                    }" class="w-full p-2 rounded border border-gray-300 focus:border-indigo-500 focus:outline-none" min="1" max="100" required>
                </div>
                <div class="bg-white p-4 rounded border-2 border-indigo-200">
                    <label class="block text-gray-600 text-sm font-bold mb-2">
                        <i class="fa-solid fa-hourglass-end text-indigo-600 mr-1"></i> Batas Giliran (Turn)
                    </label>
                    <input type="number" id="max_turns" name="max_turns" value="${
                        data.max_turns || 50
                    }" class="w-full p-2 rounded border border-gray-300 focus:border-indigo-500 focus:outline-none" min="1" max="500" required>
                </div>
                <div class="bg-gray-50 p-4 rounded border">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Versi Config</label>
                    <span class="text-indigo-600 font-mono">v${
                        data.version || 1
                    }</span>
                </div>
                <div class="flex gap-3">
                    <button type="submit" onclick="saveConfig(event)" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                    <button type="button" onclick="resetConfigForm()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 rounded-lg transition">
                        <i class="fa-solid fa-undo"></i> Reset
                    </button>
                </div>
            </form>
            <p class="text-xs text-gray-400 mt-4"><i class="fa-solid fa-info-circle mr-1"></i> Versi config akan otomatis bertambah setiap kali perubahan disimpan.</p>
        </div>
    `;
}

// --- 2. RENDER TILES (PETA) ---
function renderTiles(tiles) {
    const container = document.getElementById("settings-content");
    if (!tiles.length) {
        container.innerHTML = "Data Peta kosong.";
        return;
    }

    let rows = tiles
        .map(
            (t) => `
        <tr class="hover:bg-gray-50 border-b transition">
            <td class="px-4 py-3 text-center font-bold text-gray-600">${
                t.position
            }</td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 rounded text-xs font-bold uppercase 
                    ${
                        t.type === "risk"
                            ? "bg-red-100 text-red-700"
                            : t.type === "chance"
                            ? "bg-green-100 text-green-700"
                            : t.type === "quiz"
                            ? "bg-yellow-100 text-yellow-700"
                            : "bg-blue-100 text-blue-700"
                    }">
                    ${t.type}
                </span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-800">${t.name || "-"}</td>
            <td class="px-4 py-3 text-xs font-mono text-gray-500">${
                t.content_id ? "ID: " + t.content_id : "(Random/Empty)"
            }</td>
            <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fa-solid fa-user-check mr-1"></i> ${
                        t.landed_count || 0
                    }x
                </span>
            </td>
            <td class="px-4 py-3 text-right">
                <button onclick="showTileDetail('${
                    t.tile_id
                }')" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold border border-indigo-600 px-2 py-1 rounded hover:bg-indigo-50">
                    Lihat
                </button>
            </td>
        </tr>
    `
        )
        .join("");

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
    const container = document.getElementById("settings-content");

    let cards = items
        .map(
            (i) => `
        <div class="border-l-4 ${
            i.ui_color === "red"
                ? "border-red-500"
                : i.ui_color === "orange"
                ? "border-orange-500"
                : "border-yellow-500"
        } bg-white shadow-sm rounded p-4 mb-4 transition hover:shadow-md">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Level ${
                        i.level_id
                    } • ${i.risk_label}</span>
                    <h4 class="text-md font-bold text-gray-800 mt-1">${
                        i.title
                    }</h4>
                    <p class="text-gray-600 mt-2 text-sm italic">"${
                        i.message
                    }"</p>
                </div>
                <div class="flex gap-2 ml-2">
                    <button onclick="editIntervention(${
                        i.id
                    })" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold border border-indigo-200 px-2 py-1 rounded hover:bg-indigo-50 transition">
                        <i class="fa-solid fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteIntervention(${
                        i.id
                    })" class="text-red-600 hover:text-red-900 text-sm font-bold border border-red-200 px-2 py-1 rounded hover:bg-red-50 transition">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 flex gap-2 flex-wrap items-center">
                <span class="text-xs text-gray-400">Tombol:</span>
                ${
                    i.actions
                        ? i.actions
                              .map(
                                  (a) =>
                                      `<span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded border border-gray-300">${a.text}</span>`
                              )
                              .join("")
                        : "-"
                }
                ${
                    i.is_mandatory
                        ? '<span class="ml-auto bg-red-100 text-red-800 text-[10px] px-2 py-1 rounded font-bold"><i class="fa-solid fa-lock mr-1"></i> WAJIB</span>'
                        : ""
                }
            </div>
        </div>
    `
        )
        .join("");

    container.innerHTML = `
        <div>
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-700">Template Pesan AI (Intervensi)</h3>
                <button onclick="showAddInterventionModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Template
                </button>
            </div>
            ${
                items.length
                    ? `<div class="grid grid-cols-1 gap-2 max-w-4xl">${cards}</div>`
                    : '<p class="text-gray-500">Belum ada template intervensi.</p>'
            }
        </div>
    `;
}

// --- 4. LOGIC MODAL TILE DETAIL ---
async function showTileDetail(id) {
    const modal = document.getElementById("tile-modal");
    const body = document.getElementById("modal-body");

    modal.classList.remove("hidden"); // Tampilkan Modal
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
                    <h4 class="text-xl font-bold text-gray-800">${
                        t.name || t.default_name || t.tile_id
                    }</h4>
                    <span class="text-xs font-mono text-gray-500">ID: ${
                        t.tile_id
                    }</span>
                </div>
                
                <div class="bg-gray-50 p-3 rounded border">
                    <p class="text-xs text-gray-500 uppercase">Tipe Kotak</p>
                    <p class="font-bold text-gray-800">${t.type}</p>
                </div>

                <div class="bg-gray-50 p-3 rounded border">
                    <p class="text-xs text-gray-500 uppercase">Konten Tertaut</p>
                    <p class="font-medium text-indigo-600">${
                        t.content_title || "-"
                    }</p>
                    <p class="text-xs text-gray-400">${
                        t.content_id
                            ? t.content_type + ": " + t.content_id
                            : "Tidak ada konten khusus"
                    }</p>
                </div>

                <div class="bg-blue-50 p-3 rounded border border-blue-100 text-center">
                    <p class="text-xs text-blue-500 uppercase mb-2"><i class="fa-solid fa-chart-line mr-1"></i> Statistik Pendaratan</p>
                    <p class="text-3xl font-bold text-blue-700">${
                        t.landed_count || 0
                    }</p>
                    <p class="text-xs text-blue-600 mt-1">kali dikunjungi pemain</p>
                </div>
            </div>
        `;
    } catch (e) {
        body.innerHTML = `<p class="text-red-500">Gagal memuat detail: ${e.message}</p>`;
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
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium transition z-40 ${
        type === "success"
            ? "bg-green-500"
            : type === "error"
            ? "bg-red-500"
            : "bg-blue-500"
    }`;
    notification.innerHTML = `
        ${
            type === "success"
                ? '<i class="fa-solid fa-check-circle mr-2"></i>'
                : ""
        }
        ${
            type === "error"
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
                            <input type="number" name="level" value="${
                                data.level_id
                            }" min="1" max="10" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Risiko</label>
                            <select name="risk_level" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                                <option value="Critical" ${
                                    data.risk_label === "Critical"
                                        ? "selected"
                                        : ""
                                }>Critical</option>
                                <option value="High" ${
                                    data.risk_label === "High" ? "selected" : ""
                                }>High</option>
                                <option value="Medium" ${
                                    data.risk_label === "Medium"
                                        ? "selected"
                                        : ""
                                }>Medium</option>
                                <option value="Low" ${
                                    data.risk_label === "Low" ? "selected" : ""
                                }>Low</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul</label>
                            <input type="text" name="title_template" value="${
                                data.title
                            }" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pesan</label>
                            <textarea name="message_template" rows="3" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none">${
                                data.message
                            }</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tombol (JSON)</label>
                            <textarea name="actions_json" rows="3" required class="w-full p-2 border border-gray-300 rounded focus:border-indigo-500 focus:outline-none font-mono text-xs">${JSON.stringify(
                                data.actions
                            )}</textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_mandatory" id="is_mandatory" ${
                                data.is_mandatory ? "checked" : ""
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
