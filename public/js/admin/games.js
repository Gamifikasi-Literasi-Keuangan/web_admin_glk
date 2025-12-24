const headers = {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
};

document.addEventListener('DOMContentLoaded', () => loadData());

// --- DATA LOADER ---
async function loadData() {
    const container = document.getElementById('games-content');
    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 max-w-5xl mx-auto" id="session-grid">
            <div class="col-span-2 flex justify-center">
                <div class="loader"></div>
            </div>
        </div>
    `;

    try {
        const url = `${BASE_API}/sessions`;
        const res = await fetch(url, { headers });
        
        if (res.status === 401) {
            container.innerHTML = `
                <div class="bg-red-900/50 border border-red-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-center">
                    <p class="text-red-400 font-['Poppins'] text-sm md:text-base">Sesi habis. Silakan login ulang.</p>
                </div>
            `;
            return;
        }
        
        if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        
        const json = await res.json();
        
        // Handle different API response formats
        const sessions = json.data || json.sessions || json || [];
        
        renderSessionGrid(sessions);

    } catch (e) {
        console.error('Load sessions error:', e);
        container.innerHTML = `
            <div class="bg-red-900/50 border border-red-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-center">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl md:text-3xl mb-3 md:mb-4"></i>
                <p class="text-red-400 font-['Poppins'] font-semibold text-sm md:text-base">Error loading sessions</p>
                <p class="text-red-300 font-['Poppins'] text-xs md:text-sm mt-2">${e.message}</p>
                <button onclick="loadData()" class="mt-3 md:mt-4 bg-red-600 hover:bg-red-700 text-white px-3 md:px-4 py-2 rounded-lg font-['Poppins'] text-sm md:text-base">Retry</button>
            </div>
        `;
    }
}

// --- RENDER SESSION GRID ---
function renderSessionGrid(sessions) {
    const container = document.getElementById('games-content');
    
    if (!sessions || sessions.length === 0) { 
        container.innerHTML = `
            <div class="text-center py-12 md:py-16">
                <i class="fas fa-gamepad text-zinc-400 text-3xl md:text-4xl mb-3 md:mb-4"></i>
                <p class="text-zinc-400 text-base md:text-lg font-['Poppins']">Belum ada sesi permainan.</p>
            </div>
        `; 
        return; 
    }

    const cards = sessions.map((session, index) => {
        // Handle multiple possible field names for session ID
        const sessionId = session.session_id || session.id || session.game_id || `GAME-${String(index + 1).padStart(3, '0')}`;
        
        // Handle multiple possible field names for status
        const status = session.status || session.session_status || session.state || 'unknown';
        const isActive = status.toLowerCase() === 'active' || status.toLowerCase() === 'running' || status.toLowerCase() === 'ongoing';
        
        // Handle multiple possible field names for other data
        const playerCount = session.player_count || session.players_count || session.total_players || 0;
        const createdAt = session.created_at || session.start_time || session.date || '';
        const createdTime = createdAt ? createdAt.substring(0, 10) : 'Unknown';
        
        return `
            <div class="bg-zinc-300 rounded-[10px] p-4 md:p-6 shadow-lg hover:shadow-xl transition-all duration-300 session-card" data-session-id="${sessionId}">
                <!-- Session Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-4">
                    <h3 class="text-black text-lg md:text-2xl font-normal font-['Poppins']">Session Info</h3>
                    <div class="w-16 h-6 sm:w-20 sm:h-7 ${isActive ? 'bg-green-600' : 'bg-gray-500'} rounded-[5px] flex items-center justify-center">
                        <span class="text-white text-sm sm:text-base font-normal font-['Poppins']">${isActive ? 'Active' : 'Ended'}</span>
                    </div>
                </div>
                
                <!-- Divider Line -->
                <div class="w-full h-[1px] bg-gray-800/40 mb-3 md:mb-4"></div>
                
                <!-- Session Details -->
                <div class="mb-3 md:mb-4">
                    <p class="text-black text-sm md:text-base font-normal font-['Poppins'] mb-1 md:mb-2">Session ID</p>
                    <p class="text-black text-base md:text-xl font-bold font-['Poppins'] break-all">${sessionId}</p>
                </div>
                
                <!-- Additional Info -->
                <div class="mb-3 md:mb-4 text-xs md:text-sm">
                    <p class="text-gray-600 font-['Poppins'] mb-1">Players: ${playerCount}</p>
                    <p class="text-gray-600 font-['Poppins']">Created: ${createdTime}</p>
                </div>
                
                <!-- Detail Button -->
                <div class="flex justify-end">
                    <button onclick="showSessionDetail('${sessionId}')" class="w-20 h-6 md:w-24 md:h-7 bg-zinc-400 rounded-[5px] flex items-center justify-center hover:bg-zinc-500 transition-colors">
                        <span class="text-black text-sm md:text-base font-normal font-['Poppins']">Detail</span>
                    </button>
                </div>
            </div>
        `;
    }).join('');

    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 max-w-5xl mx-auto" id="session-grid">
            ${cards}
        </div>
    `;
}

// --- SESSION DETAIL VIEW ---
async function showSessionDetail(sessionId) {
    const container = document.getElementById('games-content');
    
    // Show loading state
    container.innerHTML = `
        <div class="flex flex-col sm:flex-row justify-center items-center py-8 md:py-12 gap-3 md:gap-4">
            <div class="loader"></div>
            <p class="text-zinc-400 font-['Poppins'] text-sm md:text-base">Loading session details...</p>
        </div>
    `;

    try {
        // Try multiple possible endpoints
        let res;
        const possibleUrls = [
            `${BASE_API}/sessions/${sessionId}`,
            `${BASE_API}/sessions/${sessionId}/detail`,
            `${BASE_API}/games/${sessionId}`,
            `${BASE_API}/game-sessions/${sessionId}`
        ];

        for (const url of possibleUrls) {
            try {
                res = await fetch(url, { headers });
                if (res.ok) break;
            } catch (e) {
                continue;
            }
        }

        if (!res || !res.ok) throw new Error("Session detail not found");
        
        const json = await res.json();
        
        // Handle multiple possible response structures
        const sessionData = json.data || json.session || json;
        const info = sessionData.session_info || sessionData.info || sessionData;
        const players = sessionData.leaderboard || sessionData.players || sessionData.participants || [];
        const currentTurn = sessionData.current_turn || sessionData.current_player || {};
        
        // Extract data with multiple fallback options
        const sessionStatus = info.status || info.session_status || info.state || 'active';
        const isActive = sessionStatus.toLowerCase() === 'active' || sessionStatus.toLowerCase() === 'running';
        
        const currentPlayerName = currentTurn.player_name || currentTurn.name || 
                                 info.current_player || info.current_player_name || 'Waiting...';
        
        const playerCount = players.length || info.player_count || info.players_count || info.total_players || 0;
        
        const duration = info.duration || info.game_duration || info.elapsed_time || 
                        info.play_time || calculateDuration(info.created_at, info.updated_at) || '0m';

        // Generate detail view
        container.innerHTML = `
            <!-- Back Button -->
            <div class="mb-4 md:mb-6">
                <button onclick="backToSessionList()" class="flex items-center gap-2 md:gap-3 text-gray-600 hover:text-black font-medium font-['Poppins'] transition-colors group">
                    <div class="bg-zinc-400 hover:bg-zinc-500 p-2 rounded-lg transition-colors group-hover:scale-105">
                        <i class="fa-solid fa-arrow-left text-base md:text-lg"></i>
                    </div>
                    <span class="text-base md:text-lg">Kembali ke Daftar Session</span>
                </button>
            </div>

            <!-- Session Detail Content -->
            <div class="max-w-7xl mx-auto px-3 md:px-6">
                <!-- Top Section -->
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 md:gap-6 mb-4 md:mb-6">
                    <!-- Session Info Card -->
                    <div class="lg:col-span-2 bg-zinc-300 rounded-[10px] p-4 md:p-6 min-h-[100px] md:min-h-[128px]">
                        <h3 class="text-black text-lg md:text-2xl font-normal font-['Poppins'] mb-3 md:mb-4">Session Info</h3>
                        <div class="w-full h-[1px] bg-gray-800/40 mb-3 md:mb-4"></div>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                            <div>
                                <p class="text-black text-sm md:text-base font-normal font-['Poppins'] mb-1">Session ID</p>
                                <p class="text-black text-base md:text-xl font-bold font-['Poppins'] break-all">${sessionId}</p>
                            </div>
                            <div class="w-16 h-5 sm:w-20 sm:h-6 ${isActive ? 'bg-green-600' : 'bg-gray-500'} rounded-[5px] flex items-center justify-center">
                                <span class="text-white text-xs sm:text-base font-normal font-['Poppins']">${isActive ? 'Active' : 'Ended'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Turn Card -->
                    <div class="lg:col-span-3 bg-zinc-300 rounded-[10px] p-4 md:p-6 min-h-[100px] md:min-h-[128px]">
                        <h3 class="text-black text-lg md:text-2xl font-normal font-['Poppins'] mb-3 md:mb-4">Giliran Anda</h3>
                        <div class="w-full h-[1px] bg-gray-800/40 mb-3 md:mb-4"></div>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
                            <div>
                                <p class="text-black text-sm md:text-base font-normal font-['Poppins']">Player Name</p>
                                <p class="text-black text-base md:text-lg font-semibold font-['Poppins'] break-words">${currentPlayerName}</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-black text-xs md:text-sm font-normal font-['Poppins']">Players: ${playerCount}</p>
                                <p class="text-black text-xs md:text-sm font-normal font-['Poppins']">Duration: ${duration}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Player Leaderboard -->
                <div class="bg-zinc-300 rounded-[10px] p-4 md:p-6">
                    <h3 class="text-black text-lg md:text-2xl font-normal font-['Poppins'] mb-3 md:mb-4">Player Leaderboard</h3>
                    <div class="w-full h-[1px] bg-gray-800/40 mb-4 md:mb-6"></div>
                    <div class="space-y-3 md:space-y-4">
                        ${players.length > 0 ? players.map((player, index) => {
                            const playerName = player.name || player.player_name || player.username || `Player ${index + 1}`;
                            const playerScore = player.score || player.final_score || player.points || player.total_score || '0';
                            const playerId = player.player_id || player.id || player.user_id || `player-${index}`;
                            
                            return `
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-black text-lg md:text-2xl font-normal font-['Poppins'] truncate">${playerName}</p>
                                    </div>
                                    <div class="flex items-center gap-3 md:gap-4 w-full sm:w-auto justify-between sm:justify-end">
                                        <div class="text-left sm:text-right">
                                            <p class="text-black text-lg md:text-2xl font-normal font-['Poppins']">${playerScore} pts</p>
                                        </div>
                                        <div class="w-20 h-6 md:w-24 md:h-7 bg-zinc-400 rounded-[5px] flex items-center justify-center hover:bg-zinc-500 transition-colors cursor-pointer" onclick="showPlayerDetail('${playerId}')">
                                            <span class="text-black text-sm md:text-base font-normal font-['Poppins']">Detail</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('') : `
                            <div class="text-center py-6 md:py-8">
                                <i class="fas fa-users text-zinc-500 text-2xl md:text-3xl mb-2 md:mb-3"></i>
                                <p class="text-zinc-600 font-['Poppins'] text-sm md:text-base">Belum ada data pemain untuk session ini</p>
                            </div>
                        `}
                    </div>
                </div>
            </div>
        `;

    } catch (e) {
        console.error('Session detail error:', e);
        container.innerHTML = `
            <div class="mb-4 md:mb-6">
                <button onclick="backToSessionList()" class="flex items-center gap-2 md:gap-3 text-gray-600 hover:text-black font-medium font-['Poppins'] transition-colors group">
                    <div class="bg-zinc-400 hover:bg-zinc-500 p-2 rounded-lg transition-colors group-hover:scale-105">
                        <i class="fa-solid fa-arrow-left text-base md:text-lg"></i>
                    </div>
                    <span class="text-base md:text-lg">Kembali ke Daftar Session</span>
                </button>
            </div>
            <div class="bg-red-900/50 border border-red-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-center max-w-2xl mx-auto">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl md:text-3xl mb-3 md:mb-4"></i>
                <p class="text-red-400 font-['Poppins'] font-semibold text-sm md:text-base">Error loading session details</p>
                <p class="text-red-300 font-['Poppins'] text-xs md:text-sm mt-2">${e.message}</p>
                <button onclick="backToSessionList()" class="mt-3 md:mt-4 bg-red-600 hover:bg-red-700 text-white px-3 md:px-4 py-2 rounded-lg font-['Poppins'] text-sm md:text-base">Back to Sessions</button>
            </div>
        `;
    }
}

// --- UTILITY FUNCTION ---
function calculateDuration(startTime, endTime) {
    if (!startTime) return '0m';
    
    const start = new Date(startTime);
    const end = endTime ? new Date(endTime) : new Date();
    const diffMs = end - start;
    const diffMins = Math.floor(diffMs / 60000);
    
    if (diffMins < 60) return `${diffMins}m`;
    const hours = Math.floor(diffMins / 60);
    const mins = diffMins % 60;
    return `${hours}h ${mins}m`;
}

// --- BACK TO SESSION LIST FUNCTION ---
function backToSessionList() {
    loadData(); // Reload the main session list
}

// --- SHOW PLAYER DETAIL FUNCTION ---
function showPlayerDetail(playerId) {
    // Navigate to players page with specific player detail
    if (window.renderPlayerDetail && typeof window.renderPlayerDetail === 'function') {
        // If we're in a context where player detail function exists
        window.renderPlayerDetail(playerId);
    } else {
        // Fallback: redirect to players page
        window.location.href = `/admin/players#player-${playerId}`;
    }
}

// No longer needed - using direct content replacement instead of modal