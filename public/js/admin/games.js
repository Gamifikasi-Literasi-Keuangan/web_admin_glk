const headers = {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
};

document.addEventListener('DOMContentLoaded', () => loadData());

// --- DATA LOADER ---
async function loadData() {
    const container = document.getElementById('session-grid');
    container.innerHTML = '<div class="col-span-2 flex justify-center"><div class="loader"></div></div>';

    try {
        const url = `${BASE_API}/sessions?limit=6`; // Load 6 sessions for grid display
        const res = await fetch(url, { headers });
        if (!res.ok) throw new Error("Gagal mengambil data");
        const json = await res.json();

        renderSessionGrid(json.data || []);

    } catch (e) {
        container.innerHTML = `<div class="col-span-2 text-red-400 p-4 text-center">Error: ${e.message}</div>`;
    }
}

// --- RENDER SESSION GRID ---
function renderSessionGrid(data) {
    const container = document.getElementById('session-grid');
    
    if (data.length === 0) { 
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-gamepad text-zinc-400 text-4xl mb-4"></i>
                <p class="text-zinc-400 text-lg font-['Poppins']">Belum ada sesi permainan.</p>
            </div>
        `; 
        return; 
    }

    // Generate session cards using modern design
    const cards = data.map((session, index) => {
        const sessionId = session.session_id || `GAME-${String(index + 1).padStart(3, '0')}`;
        const isActive = session.status && session.status.toLowerCase() === 'active';
        const playerCount = session.player_count || 0;
        const createdTime = session.created_at || 'Unknown';
        
        return `
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 session-card" data-session-id="${sessionId}">
                <!-- Session Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-white text-lg font-bold font-['Poppins'] mb-1">Session Info</h3>
                        <p class="text-zinc-400 text-sm font-['Poppins']">Game Session Details</p>
                    </div>
                    <div class="session-status-badge px-3 py-1 rounded-full text-xs font-semibold ${isActive ? 'bg-green-500 text-white' : 'bg-zinc-600 text-zinc-300'}">
                        <span class="session-status-text">${isActive ? 'Active' : 'Ended'}</span>
                    </div>
                </div>
                
                <!-- Session Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-zinc-700/50 rounded-lg p-3 border border-zinc-600">
                        <p class="text-zinc-400 text-xs font-['Poppins'] mb-1">Players</p>
                        <p class="text-white text-lg font-bold font-['Poppins']">${playerCount}</p>
                    </div>
                    <div class="bg-zinc-700/50 rounded-lg p-3 border border-zinc-600">
                        <p class="text-zinc-400 text-xs font-['Poppins'] mb-1">Duration</p>
                        <p class="text-white text-lg font-bold font-['Poppins']">${session.duration || '0m'}</p>
                    </div>
                </div>
                
                <!-- Divider -->
                <div class="border-t border-zinc-600 my-4"></div>
                
                <!-- Session Details -->
                <div class="space-y-3">
                    <div>
                        <p class="text-zinc-400 text-sm font-['Poppins'] mb-1">Session ID</p>
                        <p class="text-white text-xl font-bold font-['Poppins'] session-id-value">${sessionId}</p>
                    </div>
                    
                    <div>
                        <p class="text-zinc-400 text-sm font-['Poppins'] mb-1">Created</p>
                        <p class="text-zinc-300 text-sm font-['Poppins']">${createdTime}</p>
                    </div>
                    
                    <!-- Action Button -->
                    <button onclick="showSessionDetail('${sessionId}')" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold font-['Poppins'] transition-all duration-200 flex items-center justify-center group session-detail-btn">
                        <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform"></i>
                        View Details
                    </button>
                </div>
            </div>
        `;
    }).join('');

    container.innerHTML = cards;
}

// --- SESSION DETAIL MODAL ---
async function showSessionDetail(sessionId) {
    const modal = document.getElementById('session-modal');
    const body = document.getElementById('modal-body');
    document.getElementById('modal-title').innerText = 'Session Details';
    document.getElementById('modal-subtitle').innerText = `ID: ${sessionId}`;
    
    modal.classList.remove('hidden');
    body.innerHTML = `
        <div class="flex justify-center items-center py-12">
            <div class="loader"></div>
            <p class="ml-4 text-zinc-400 font-['Poppins']">Loading session details...</p>
        </div>
    `;

    try {
        const res = await fetch(`${BASE_API}/sessions/${sessionId}`, { headers });
        if (!res.ok) throw new Error("Gagal memuat detail");
        const json = await res.json();
        
        const info = json.session_info;
        const players = json.leaderboard || [];
        const timeline = json.timeline_logs || [];

        // Session Info Card with modern design
        let html = `
            <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-white font-['Poppins'] mb-4 flex items-center">
                    <i class="fas fa-info-circle text-green-500 mr-3"></i>
                    Session Information
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-zinc-700 rounded-lg p-4">
                        <span class="text-sm text-zinc-400 block font-['Poppins'] mb-1">Host</span>
                        <span class="font-bold text-white font-['Poppins']">${info.host || '-'}</span>
                    </div>
                    <div class="bg-zinc-700 rounded-lg p-4">
                        <span class="text-sm text-zinc-400 block font-['Poppins'] mb-1">Status</span>
                        <span class="font-bold text-green-400 uppercase font-['Poppins']">${info.status}</span>
                    </div>
                    <div class="bg-zinc-700 rounded-lg p-4">
                        <span class="text-sm text-zinc-400 block font-['Poppins'] mb-1">Duration</span>
                        <span class="font-bold text-white font-['Poppins']">${info.duration}</span>
                    </div>
                    <div class="bg-zinc-700 rounded-lg p-4">
                        <span class="text-sm text-zinc-400 block font-['Poppins'] mb-1">Total Turns</span>
                        <span class="font-bold text-white font-['Poppins']">${info.total_turns}</span>
                    </div>
                </div>
            </div>
        `;

        // Players Leaderboard
        html += `
            <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-white font-['Poppins'] mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-400 mr-3"></i>
                    Match Results
                </h3>
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gradient-to-r from-green-600 to-green-700">
                            <tr>
                                <th class="p-4 text-left text-white font-['Poppins'] font-semibold rounded-tl-lg">Rank</th>
                                <th class="p-4 text-left text-white font-['Poppins'] font-semibold">Player</th>
                                <th class="p-4 text-center text-white font-['Poppins'] font-semibold">Final Score</th>
                                <th class="p-4 text-center text-white font-['Poppins'] font-semibold rounded-tr-lg">Board Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${players.map((p, index) => `
                                <tr class="border-b border-zinc-700 hover:bg-zinc-700/50 transition-colors">
                                    <td class="p-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white ${index === 0 ? 'bg-yellow-500' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-orange-500' : 'bg-blue-500'}">
                                                ${p.rank}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 font-['Poppins'] font-medium text-white">${p.name}</td>
                                    <td class="p-4 text-center font-bold text-green-400 font-['Poppins']">${p.score}</td>
                                    <td class="p-4 text-center font-['Poppins'] text-zinc-400">Position #${p.final_tile_position}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;

        // Timeline Log with modern design
        html += `
            <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-white font-['Poppins'] mb-4 flex items-center">
                    <i class="fas fa-history text-blue-400 mr-3"></i>
                    Game Timeline
                </h3>
                <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                    ${timeline.map(log => `
                        <div class="bg-zinc-700 rounded-lg p-4 border-l-4 border-green-500">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center">
                                    <div class="bg-green-500 text-white w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs mr-3">
                                        ${log.turn_number}
                                    </div>
                                    <span class="font-bold text-white font-['Poppins']">${log.player}</span>
                                </div>
                                <span class="text-xs text-zinc-400 font-['Poppins']">${log.timestamp}</span>
                            </div>
                            
                            ${log.activity.dice_roll ? `<div class="text-sm mb-2 font-['Poppins'] text-zinc-300">🎲 Dice Roll: <strong class="text-green-400">${log.activity.dice_roll}</strong></div>` : ''}

                            ${log.activity.decisions.map(d => `
                                <div class="mt-2 text-sm bg-zinc-600 rounded p-3 border ${d.result === 'Correct' ? 'border-green-500 bg-green-500/10' : 'border-red-500 bg-red-500/10'}">
                                    <div class="flex justify-between items-center">
                                        <span class="uppercase font-bold text-xs text-zinc-400 font-['Poppins']">${d.type}</span>
                                        <span class="${d.result === 'Correct' ? 'text-green-400 bg-green-500/20' : 'text-red-400 bg-red-500/20'} px-2 py-1 rounded-full text-xs font-semibold font-['Poppins']">
                                            ${d.result}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-zinc-300 font-['Poppins']">${d.impact}</span>
                                        <span class="text-zinc-400 ml-2 text-xs font-['Poppins']">• ${d.thinking_time}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        body.innerHTML = html;

    } catch (e) {
        body.innerHTML = `
            <div class="bg-red-900/50 border border-red-600 rounded-2xl p-6 text-center">
                <i class="fas fa-exclamation-triangle text-red-400 text-3xl mb-4"></i>
                <p class="text-red-400 font-['Poppins'] font-semibold">Error loading session details</p>
                <p class="text-red-300 font-['Poppins'] text-sm mt-2">${e.message}</p>
            </div>
        `;
    }
}

function closeModal() {
    document.getElementById('session-modal').classList.add('hidden');
}