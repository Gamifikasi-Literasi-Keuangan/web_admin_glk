const headers = {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
};
let currentTab = 'sessions';

document.addEventListener('DOMContentLoaded', () => loadData());

// --- TAB SWITCHING ---
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

// --- DATA LOADER ---
async function loadData() {
    const container = document.getElementById('games-content');
    container.innerHTML = '<div class="loader"></div>';

    try {
        let url = currentTab === 'sessions' 
            ? `${BASE_API}/sessions?limit=20` 
            : `${BASE_API}/leaderboard/global?limit=50`;

        const res = await fetch(url, { headers });
        if (!res.ok) throw new Error("Gagal mengambil data");
        const json = await res.json();

        if (currentTab === 'sessions') renderSessionList(json.data || []);
        else renderGlobalLeaderboard(json.rankings || []);

    } catch (e) {
        container.innerHTML = `<div class="text-red-500 p-4">Error: ${e.message}</div>`;
    }
}

// --- 1. RENDER SESSION LIST ---
function renderSessionList(data) {
    const container = document.getElementById('games-content');
    if (data.length === 0) { container.innerHTML = 'Belum ada riwayat permainan.'; return; }

    let rows = data.map(s => `
        <tr class="hover:bg-gray-50 border-b">
            <td class="px-5 py-4">
                <div class="text-sm font-bold text-gray-900">${s.session_id}</div>
                <div class="text-xs text-gray-500">${s.played_at}</div>
            </td>
            <td class="px-5 py-4">
                <span class="px-2 py-1 rounded-full text-xs font-bold uppercase 
                    ${s.status.toLowerCase() === 'completed' ? 'bg-green-100 text-green-700' : 
                      (s.status.toLowerCase() === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600')}">
                    ${s.status}
                </span>
            </td>
            <td class="px-5 py-4 text-sm text-gray-700">
                ${s.winner !== '-' ? `<i class="fa-solid fa-crown text-yellow-500 mr-1"></i> ${s.winner} (${s.winning_score})` : '-'}
            </td>
            <td class="px-5 py-4 text-sm text-gray-500">${s.duration_human}</td>
            <td class="px-5 py-4 text-right">
                <button onclick="showSessionDetail('${s.session_id}')" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm border border-indigo-200 px-3 py-1 rounded hover:bg-indigo-50">
                    Detail & Replay
                </button>
            </td>
        </tr>
    `).join('');

    container.innerHTML = `
    <div class="max-w-4xl mx-auto bg-white rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-indigo-50 p-4 ...">...</div>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                        <th class="px-5 py-3">ID Sesi</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Pemenang</th>
                        <th class="px-5 py-3">Durasi</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
    `;
}

// --- 2. RENDER GLOBAL LEADERBOARD ---
function renderGlobalLeaderboard(data) {
    const container = document.getElementById('games-content');
    if (data.length === 0) { container.innerHTML = 'Belum ada data peringkat.'; return; }

    let rows = data.map((p, index) => {
        let rankBadge = index < 3 
            ? `<div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-bold ${index === 0 ? 'bg-yellow-500' : (index === 1 ? 'bg-gray-400' : 'bg-orange-400')}">#${p.rank}</div>`
            : `<span class="font-bold text-gray-500 ml-2">#${p.rank}</span>`;

        return `
        <tr class="hover:bg-gray-50 border-b">
            <td class="px-5 py-4 w-16">${rankBadge}</td>
            <td class="px-5 py-4">
                <div class="font-bold text-gray-800 text-lg">${p.name}</div>
                <div class="text-xs text-gray-500">@${p.username}</div>
            </td>
            <td class="px-5 py-4 text-center">
                <div class="text-indigo-600 font-bold text-lg">${p.total_score}</div>
                <div class="text-xs text-gray-400">Total Skor</div>
            </td>
            <td class="px-5 py-4 text-center">
                <div class="text-gray-800 font-bold">${p.total_games}</div>
                <div class="text-xs text-gray-400">Kali Main</div>
            </td>
            <td class="px-5 py-4 text-center">
                <div class="text-green-600 font-bold">${p.avg_score}</div>
                <div class="text-xs text-gray-400">Rata-rata</div>
            </td>
        </tr>
        `;
    }).join('');

    container.innerHTML = `
        <div class="max-w-4xl mx-auto bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="bg-indigo-50 p-4 border-b border-indigo-100 flex items-center justify-between">
                <h3 class="font-bold text-indigo-800"><i class="fa-solid fa-medal mr-2"></i> Top Players Global</h3>
                <span class="text-xs text-indigo-600 bg-white px-2 py-1 rounded">Update: Realtime</span>
            </div>
            <table class="min-w-full">
                <tbody>${rows}</tbody>
            </table>
        </div>
    `;
}

// --- 3. MODAL DETAIL SESI (Termasuk Leaderboard Sesi) ---
async function showSessionDetail(sessionId) {
    const modal = document.getElementById('session-modal');
    const body = document.getElementById('modal-body');
    document.getElementById('modal-subtitle').innerText = `ID: ${sessionId}`;
    
    modal.classList.remove('hidden');
    body.innerHTML = '<div class="loader"></div>';

    try {
        const res = await fetch(`${BASE_API}/sessions/${sessionId}`, { headers });
        if (!res.ok) throw new Error("Gagal memuat detail");
        const json = await res.json();
        
        const info = json.session_info;
        const players = json.leaderboard || []; // Ini Leaderboard Per Sesi
        const timeline = json.timeline_logs || [];

        // A. Render Info
        let html = `
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg border">
                <div><span class="text-xs text-gray-500 block">Host</span><span class="font-bold">${info.host || '-'}</span></div>
                <div><span class="text-xs text-gray-500 block">Status</span><span class="font-bold uppercase text-indigo-600">${info.status}</span></div>
                <div><span class="text-xs text-gray-500 block">Durasi</span><span class="font-bold">${info.duration}</span></div>
                <div><span class="text-xs text-gray-500 block">Total Giliran</span><span class="font-bold">${info.total_turns}</span></div>
            </div>
        `;

        // B. Render Leaderboard Sesi (Yang Anda Minta)
        html += `
            <div>
                <h4 class="font-bold text-gray-700 mb-3 border-l-4 border-green-500 pl-2">🏆 Hasil Pertandingan (Leaderboard)</h4>
                <div class="overflow-x-auto bg-white border rounded-lg">
                    <table class="min-w-full text-sm">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="p-3 text-left">Rank</th>
                                <th class="p-3 text-left">Nama Pemain</th>
                                <th class="p-3 text-center">Skor Akhir</th>
                                <th class="p-3 text-center">Posisi Papan</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${players.map(p => `
                                <tr class="border-b">
                                    <td class="p-3 font-bold">#${p.rank}</td>
                                    <td class="p-3">${p.name}</td>
                                    <td class="p-3 text-center font-bold text-green-700">${p.score}</td>
                                    <td class="p-3 text-center">Kotak #${p.final_tile_position}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;

        // C. Render Timeline / Replay Log
        html += `
            <div>
                <h4 class="font-bold text-gray-700 mb-3 border-l-4 border-blue-500 pl-2">📜 Log Permainan (Timeline)</h4>
                <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                    ${timeline.map(log => `
                        <div class="flex gap-3 items-start bg-white p-3 rounded border hover:bg-gray-50">
                            <div class="bg-gray-200 text-gray-600 w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0">
                                ${log.turn_number}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="font-bold text-sm text-gray-800">${log.player}</span>
                                    <span class="text-xs text-gray-400">${log.timestamp}</span>
                                </div>
                                
                                ${log.activity.dice_roll ? `<div class="text-xs mt-1">🎲 Melempar dadu: <strong>${log.activity.dice_roll}</strong></div>` : ''}

                                ${log.activity.decisions.map(d => `
                                    <div class="mt-2 text-xs border-l-2 ${d.result === 'Correct' ? 'border-green-400 bg-green-50' : 'border-red-400 bg-red-50'} p-2 rounded">
                                        <span class="uppercase font-bold text-[10px] text-gray-500">${d.type}</span><br>
                                        <span class="${d.result === 'Correct' ? 'text-green-700' : 'text-red-700'} font-semibold">
                                            ${d.result} (${d.impact})
                                        </span>
                                        <span class="text-gray-400 ml-2">• ${d.thinking_time}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        body.innerHTML = html;

    } catch (e) {
        body.innerHTML = `<div class="text-red-500 p-4 text-center">Error: ${e.message}</div>`;
    }
}

function closeModal() {
    document.getElementById('session-modal').classList.add('hidden');
}