const headers = {
    Authorization: `Bearer ${token}`, // token diambil dari layout utama
    Accept: "application/json",
};

// --- INIT ---
document.addEventListener("DOMContentLoaded", () => {
    renderPlayerList();
});

// --- 1. RENDER LIST PLAYERS ---
async function renderPlayerList(keyword = "") {
    const wrapper = document.getElementById("table-wrapper");
    const detailWrapper = document.getElementById("detail-wrapper");
    const container = document.getElementById("player-container");

    // Tampilkan container list, sembunyikan detail
    container.classList.remove("hidden");
    detailWrapper.classList.add("hidden");

    if (!keyword) wrapper.innerHTML = '<div class="loader"></div>';

    try {
        const url = `${BASE_API}/players?limit=20&search=${encodeURIComponent(
            keyword
        )}`;
        const response = await fetch(url, { headers });

        if (response.status === 401) {
            // Token expired or invalid - global interceptor will handle redirect
            // Just show message and return
            wrapper.innerHTML = `
                <div class="bg-red-900/50 border border-red-600 rounded-2xl p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-red-400 text-3xl mb-4"></i>
                    <p class="text-red-400 font-['Poppins'] font-semibold">Session Expired</p>
                    <p class="text-red-300 font-['Poppins'] text-sm mt-2">Redirecting to login...</p>
                </div>
            `;
            return;
        }

        const json = await response.json();
        const players = json.data || [];

        if (players.length === 0) {
            wrapper.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16">
                    <i class="fas fa-users text-zinc-400 text-4xl mb-4"></i>
                    <p class="text-zinc-400 text-lg font-['Poppins']">Tidak ditemukan data pemain.</p>
                </div>
            `;
            return;
        }

        // Header row for desktop layout
        let html = `
            <div class="hidden md:grid grid-cols-12 gap-4 mb-4 px-6 py-4 bg-zinc-700 rounded-2xl border border-zinc-600">
                <div class="col-span-4 text-violet-100 text-lg font-semibold font-['Poppins']">Player Name</div>
                <div class="col-span-3 text-violet-100 text-lg font-semibold font-['Poppins']">Cluster AI</div>
                <div class="col-span-2 text-violet-100 text-lg font-semibold font-['Poppins']">Status</div>
                <div class="col-span-3 text-violet-100 text-lg font-semibold font-['Poppins'] text-center">Aksi</div>
            </div>
            
            <div class="space-y-4">
        `;

        players.forEach((p, index) => {
            const statusColor = p.status === 'Active' ? 'bg-green-600/75' : 'bg-red-600/75';
            const statusText = p.status === 'Active' ? 'Active' : 'Deactivated';

            html += `
                <div class="bg-zinc-300 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-zinc-400 player-card" data-player-id="${p.player_id}" data-player-name="${p.name}">
                    <!-- Mobile Layout -->
                    <div class="block md:hidden space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-black text-lg font-bold font-['Poppins'] player-name">${p.name}</h3>
                                <p class="text-gray-600 text-sm font-['Poppins']">@${p.username}</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="banPlayer('${p.player_id}')" class="bg-red-600/70 hover:bg-red-600 text-white p-2 rounded-lg transition-colors action-button" title="Ban Player">
                                    <i class="fas fa-ban text-sm"></i>
                                </button>
                                <button onclick="deletePlayer('${p.player_id}')" class="bg-yellow-500/75 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors action-button" title="Delete Player">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                <button onclick="renderPlayerDetail('${p.player_id}')" class="bg-black/70 hover:bg-black text-white p-2 rounded-lg transition-colors action-button" title="View Details">
                                    <i class="fas fa-info-circle text-sm"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 text-xs font-['Poppins'] mb-1">Cluster AI</p>
                                <span class="text-black text-sm font-['Poppins']">${p.cluster || '-'}</span>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-['Poppins'] mb-1">Status</p>
                                <span class="inline-block ${statusColor} text-white px-3 py-1 rounded-lg text-sm font-semibold font-['Poppins']">
                                    ${statusText}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Layout -->
                    <div class="hidden md:grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-4">
                            <h3 class="text-black text-lg font-bold font-['Poppins'] player-name">${p.name}</h3>
                            <p class="text-gray-600 text-sm font-['Poppins']">@${p.username}</p>
                        </div>
                        
                        <div class="col-span-3">
                            <span class="text-black text-base font-['Poppins']">${p.cluster || '-'}</span>
                        </div>
                        
                        <div class="col-span-2">
                            <span class="inline-block ${statusColor} text-white px-4 py-2 rounded-lg text-base font-semibold font-['Poppins']">
                                ${statusText}
                            </span>
                        </div>
                        
                        <div class="col-span-3 flex justify-center gap-2">
                            <button onclick="banPlayer('${p.player_id}')" class="bg-red-600/70 hover:bg-red-600 text-white p-2 rounded-lg transition-colors action-button" title="Ban Player">
                                <i class="fas fa-ban text-lg"></i>
                            </button>
                            <button onclick="deletePlayer('${p.player_id}')" class="bg-yellow-500/75 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors action-button" title="Delete Player">
                                <i class="fas fa-trash text-lg"></i>
                            </button>
                            <button onclick="renderPlayerDetail('${p.player_id}')" class="bg-black/70 hover:bg-black text-white p-2 rounded-lg transition-colors action-button" title="View Details">
                                <i class="fas fa-info-circle text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        wrapper.innerHTML = html;

        // Restore focus input search agar UX nyaman
        const input = document.getElementById("searchInput");
        if (input) {
            input.focus();
            input.value = keyword;
        }
    } catch (e) {
        wrapper.innerHTML = `
            <div class="bg-red-900/50 border border-red-600 rounded-2xl p-6 text-center">
                <i class="fas fa-exclamation-triangle text-red-400 text-3xl mb-4"></i>
                <p class="text-red-400 font-['Poppins'] font-semibold">Error loading players</p>
                <p class="text-red-300 font-['Poppins'] text-sm mt-2">${e.message}</p>
            </div>
        `;
    }
}

// --- 2. RENDER DETAIL PLAYER (FULL VERSION WITH PROFILING) ---
async function renderPlayerDetail(playerId) {
    const container = document.getElementById("player-container");
    const detailWrapper = document.getElementById("detail-wrapper");

    // Switch View ke Detail
    container.classList.add("hidden");
    detailWrapper.classList.remove("hidden");
    detailWrapper.innerHTML = '<div class="loader"></div>';

    try {
        // FETCH 4 API SEKALIGUS
        const [resDetail, resAnalysis, resCurve, resSkill] = await Promise.all([
            fetch(`${BASE_API}/players/${playerId}`, { headers }),
            fetch(`${BASE_API}/players/${playerId}/analysis`, { headers }),
            fetch(
                `${BASE_API}/analytics/learning-curve?player_id=${playerId}`,
                { headers }
            ),
            fetch(`${BASE_API}/analytics/skill-matrix?player_id=${playerId}`, {
                headers,
            }),
        ]);

        const detail = await resDetail.json();
        const analysis = await resAnalysis.json();
        const curve = await resCurve.json();
        const skill = await resSkill.json();

        const p = detail.player_info;
        const ai = detail.ai_profile;
        const stats = detail.lifetime_stats || {};
        const weaknesses = analysis.weaknesses || [];
        const recommendations = analysis.recommendations || [];

        // --- 1. DEFINISI KAMUS JAWABAN PROFILING ---
        const profilingMap = {
            0: {
                // Gaji Pertama
                A: {
                    text: "50% Tabungan",
                    class: "bg-green-100 text-green-800",
                },
                B: { text: "Seimbang", class: "bg-blue-100 text-blue-800" },
                C: { text: "Gaya Hidup", class: "bg-red-100 text-red-800" },
                D: {
                    text: "Investasi",
                    class: "bg-orange-100 text-orange-800",
                },
            },
            1: {
                // Modal Bisnis
                A: {
                    text: "Tolak (Aman)",
                    class: "bg-green-100 text-green-800",
                },
                B: { text: "Ambil Pinjol", class: "bg-red-100 text-red-800" },
                C: {
                    text: "Pinjam Ortu",
                    class: "bg-yellow-100 text-yellow-800",
                },
                D: {
                    text: "Pakai Tabungan",
                    class: "bg-orange-100 text-orange-800",
                },
            },
            2: {
                // Crypto FOMO
                A: { text: "Riset Dulu", class: "bg-green-100 text-green-800" },
                B: { text: "All-in (FOMO)", class: "bg-red-100 text-red-800" },
                C: {
                    text: "Ikut Teman",
                    class: "bg-yellow-100 text-yellow-800",
                },
                D: {
                    text: "Tidak Tertarik",
                    class: "bg-gray-100 text-gray-800",
                },
            },
        };

        // --- 2. GENERATE HTML PROFILING ---
        const answers = Array.isArray(ai.initial_answers)
            ? ai.initial_answers
            : [];
        const profilingHtml = answers.length > 0 ? `
            <div class="mt-6 p-4 bg-zinc-700/50 rounded-xl border border-zinc-600">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-clipboard-list text-blue-400"></i>
                    <span class="text-sm font-bold text-zinc-300 uppercase font-['Poppins']">Profiling Awal</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    ${answers.map((ans, idx) => {
            const map = profilingMap[idx] ? profilingMap[idx][ans] : { text: ans, class: 'bg-zinc-600 text-zinc-300 border-zinc-500' };
            return `
                            <div class="text-center p-3 rounded-lg border ${map.class || 'bg-zinc-600 border-zinc-500'} transition-all hover:scale-105">
                                <div class="text-xs uppercase opacity-70 font-['Poppins'] mb-2">Question ${idx + 1}</div>
                                <div class="font-bold text-sm font-['Poppins']">${map.text || ans}</div>
                            </div>
                        `;
        })
                .join("")}
                </div>
            </div>
        ` : `
            <div class="mt-6 p-4 bg-zinc-700/30 rounded-xl border border-zinc-600/50 text-center">
                <i class="fas fa-info-circle text-zinc-500 mb-2"></i>
                <p class="text-sm text-zinc-400 italic font-['Poppins']">Belum ada data profiling awal</p>
            </div>
        `;

        // --- 3. GENERATE HTML SKILL MATRIX ---
        const skillHtml = Object.entries(skill)
            .map(
                ([k, v]) => `
            <div class="flex justify-between items-center bg-gray-50 p-2 rounded mb-1 border border-gray-100">
                <span class="text-sm font-medium text-gray-700">${k}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-white uppercase ${v === "Expert"
                        ? "bg-green-500"
                        : v === "Intermediate"
                            ? "bg-yellow-500"
                            : "bg-red-500"
                    }">
                    ${v}
                </span>
            </div>
        `
            )
            .join("");

        // --- 4. BUILD HTML UTAMA ---
        detailWrapper.innerHTML = `
            <!-- Back Button -->
            <div class="mb-6">
                <button onclick="renderPlayerList()" class="flex items-center gap-3 text-zinc-300 hover:text-white font-medium font-['Poppins'] transition-colors group">
                    <div class="bg-zinc-700 hover:bg-zinc-600 p-2 rounded-lg transition-colors group-hover:scale-105">
                        <i class="fa-solid fa-arrow-left text-lg"></i>
                    </div>
                    <span class="text-lg">Kembali ke Daftar Player</span>
                </button>
            </div>

            <!-- Player Header Card -->
            <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 mb-6 shadow-lg">
                <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                    <!-- Player Basic Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold text-white font-['Poppins']">${p.name}</h1>
                                <p class="text-zinc-400 text-lg font-['Poppins']">@${p.username}</p>
                                <p class="text-zinc-500 text-sm font-['Poppins']">Bergabung: ${p.join_date?.substring(0, 10)}</p>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button onclick="banPlayer('${p.player_id || ''}')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold font-['Poppins'] transition-all action-button flex items-center gap-2" title="Ban Player">
                                    <i class="fas fa-ban"></i>
                                    <span class="hidden sm:inline">Ban</span>
                                </button>
                                <button onclick="deletePlayer('${p.player_id || ''}')" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-semibold font-['Poppins'] transition-all action-button flex items-center gap-2" title="Delete Player">
                                    <i class="fas fa-trash"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </button>
                                <button onclick="exportPlayerData('${p.player_id || ''}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold font-['Poppins'] transition-all action-button flex items-center gap-2" title="Export Data">
                                    <i class="fas fa-download"></i>
                                    <span class="hidden sm:inline">Export</span>
                                </button>
                            </div>
                        </div>
                        
                        ${profilingHtml}
                    </div>
                    
                    <!-- AI Cluster Info -->
                    <div class="bg-gradient-to-r from-green-600/20 to-green-700/20 border border-green-500/30 p-6 rounded-xl min-w-[280px]">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-brain text-white text-xl"></i>
                            </div>
                            <p class="text-xs text-zinc-400 uppercase tracking-wide font-['Poppins'] mb-1">AI Cluster</p>
                            <h3 class="text-2xl font-bold text-green-400 font-['Poppins'] mb-2">${ai.cluster || 'Unprofiled'}</h3>
                            <p class="text-sm text-zinc-400 font-['Poppins'] mb-4">Confidence: ${ai.ai_confidence || '0%'}</p>
                            
                            <!-- AI Traits -->
                            <div class="flex flex-wrap justify-center gap-2">
                                ${ai.traits ? ai.traits.map(t => `
                                    <span class="inline-block bg-zinc-700/70 border border-zinc-600 text-zinc-300 text-xs px-3 py-1 rounded-full font-['Poppins']">
                                        ${t}
                                    </span>
                                `).join('') : '<span class="text-zinc-500 text-sm font-[\'Poppins\']">No traits data</span>'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats & Analysis Grid -->
            <div class="mb-6">
                <!-- Tab Navigation -->
                <div class="flex gap-2 mb-6 bg-zinc-800 border border-zinc-700 rounded-xl p-2">
                    <button onclick="switchTab('overview')" class="tab-btn active flex-1 px-4 py-3 rounded-lg font-semibold font-['Poppins'] transition-all" data-tab="overview">
                        <i class="fas fa-chart-bar mr-2"></i>Overview
                    </button>
                    <button onclick="switchTab('performance')" class="tab-btn flex-1 px-4 py-3 rounded-lg font-semibold font-['Poppins'] transition-all" data-tab="performance">
                        <i class="fas fa-trophy mr-2"></i>Performance
                    </button>
                    <button onclick="switchTab('analysis')" class="tab-btn flex-1 px-4 py-3 rounded-lg font-semibold font-['Poppins'] transition-all" data-tab="analysis">
                        <i class="fas fa-brain mr-2"></i>Analysis
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    
                    <!-- Overview Tab -->
                    <div class="tab-panel active grid grid-cols-1 xl:grid-cols-2 gap-6" data-panel="overview">
                        
                        <!-- Game Statistics -->
                        <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-gamepad text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white font-['Poppins']">Game Statistics</h3>
                            </div>
                            
                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <div class="bg-gradient-to-r from-blue-600/20 to-blue-700/20 border border-blue-500/30 rounded-xl p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-400 font-['Poppins'] mb-1">${stats.total_games || 0}</div>
                                    <div class="text-xs text-zinc-400 uppercase font-['Poppins']">Total Games</div>
                                </div>
                                <div class="bg-gradient-to-r from-purple-600/20 to-purple-700/20 border border-purple-500/30 rounded-xl p-4 text-center">
                                    <div class="text-2xl font-bold text-purple-400 font-['Poppins'] mb-1">${stats.avg_score || 0}</div>
                                    <div class="text-xs text-zinc-400 uppercase font-['Poppins']">Avg Score</div>
                                </div>
                                <div class="bg-gradient-to-r from-green-600/20 to-green-700/20 border border-green-500/30 rounded-xl p-4 text-center">
                                    <div class="text-2xl font-bold text-green-400 font-['Poppins'] mb-1">${stats.win_rate || '0%'}</div>
                                    <div class="text-xs text-zinc-400 uppercase font-['Poppins']">Win Rate</div>
                                </div>
                                <div class="bg-gradient-to-r from-orange-600/20 to-orange-700/20 border border-orange-500/30 rounded-xl p-4 text-center">
                                    <div class="text-2xl font-bold text-orange-400 font-['Poppins'] mb-1">${stats.total_playtime || '0h'}</div>
                                    <div class="text-xs text-zinc-400 uppercase font-['Poppins']">Playtime</div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Cluster Info Extended -->
                        <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-brain text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white font-['Poppins']">AI Analysis</h3>
                            </div>
                            
                            <div class="text-center mb-6">
                                <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user-tie text-white text-2xl"></i>
                                </div>
                                <h4 class="text-2xl font-bold text-green-400 font-['Poppins'] mb-2">${ai.cluster || 'Unprofiled'}</h4>
                                <div class="bg-zinc-700/50 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-zinc-300 font-['Poppins']">Confidence Level</p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <div class="flex-1 bg-zinc-600 rounded-full h-3">
                                            <div class="bg-green-500 h-3 rounded-full" style="width: ${ai.ai_confidence || '0%'}"></div>
                                        </div>
                                        <span class="text-green-400 font-bold font-['Poppins']">${ai.ai_confidence || '0%'}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- AI Traits -->
                            <div class="space-y-2">
                                <h5 class="text-sm font-bold text-zinc-300 mb-3 font-['Poppins']">Player Traits:</h5>
                                <div class="flex flex-wrap gap-2">
                                    ${ai.traits ? ai.traits.map(t => `
                                        <span class="bg-gradient-to-r from-purple-600/20 to-purple-700/20 border border-purple-500/30 text-purple-200 text-xs px-3 py-2 rounded-full font-['Poppins']">
                                            ${t}
                                        </span>
                                    `).join('') : '<span class="text-zinc-500 text-sm font-[\'Poppins\']">No traits identified yet</span>'}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Tab -->
                    <div class="tab-panel grid grid-cols-1 xl:grid-cols-3 gap-6 hidden" data-panel="performance">
                        
                        <!-- Learning Curve Chart -->
                        <div class="xl:col-span-2 bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-line text-white"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-white font-['Poppins']">Learning Progress</h3>
                                </div>
                                <span class="bg-zinc-700 px-3 py-1 rounded-lg text-xs text-zinc-400 font-['Poppins']">
                                    Accuracy Trend
                                </span>
                            </div>
                            <div class="h-80 bg-zinc-900/50 rounded-lg p-4">
                                <canvas id="playerCurveChart" class="w-full h-full"></canvas>
                            </div>
                        </div>

                        <!-- Skill Matrix -->
                        <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-cogs text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white font-['Poppins']">Skills</h3>
                            </div>
                            
                            <div class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar">
                                ${skillHtml || `
                                    <div class="text-center py-12">
                                        <i class="fas fa-chart-bar text-zinc-500 text-3xl mb-3"></i>
                                        <p class="text-zinc-400 text-sm font-['Poppins']">Skill data will appear after more games</p>
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>

                    <!-- Analysis Tab -->
                    <div class="tab-panel grid grid-cols-1 xl:grid-cols-2 gap-6 hidden" data-panel="analysis">
                        
                        <!-- Weakness Analysis -->
                        <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white font-['Poppins']">Areas for Improvement</h3>
                            </div>
                            
                            <div class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar">
                                ${weaknesses.length > 0 ? weaknesses.map((w, index) => `
                                    <div class="bg-gradient-to-r from-red-900/30 to-red-800/30 border border-red-600/30 rounded-xl p-4 hover:from-red-900/40 hover:to-red-800/40 transition-all">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-white text-xs font-bold">${index + 1}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-red-300 font-semibold font-['Poppins']">${w.category}</h4>
                                            </div>
                                            <div class="bg-red-600/50 px-3 py-1 rounded-full">
                                                <span class="text-white text-xs font-bold font-['Poppins']">${w.accuracy}</span>
                                            </div>
                                        </div>
                                        ${w.description ? `<p class="text-zinc-400 text-sm font-['Poppins'] ml-11">${w.description}</p>` : ''}
                                    </div>
                                `).join('') : `
                                    <div class="text-center py-12">
                                        <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-check text-green-400 text-2xl"></i>
                                        </div>
                                        <h4 class="text-green-400 font-semibold font-['Poppins'] mb-2">Great Performance!</h4>
                                        <p class="text-zinc-400 font-['Poppins']">No significant weaknesses detected</p>
                                    </div>
                                `}
                            </div>
                        </div>

                        <!-- AI Recommendations -->
                        <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white font-['Poppins']">AI Recommendations</h3>
                            </div>
                            
                            <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar">
                                ${recommendations.length > 0 ? recommendations.map((rec, index) => `
                                    <div class="bg-gradient-to-r from-purple-600/20 to-purple-700/20 border border-purple-500/30 rounded-xl p-4 hover:from-purple-600/30 hover:to-purple-700/30 transition-all duration-200">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                                <span class="text-white text-xs font-bold font-['Poppins']">${index + 1}</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="bg-purple-500/50 px-2 py-1 rounded text-xs font-bold text-purple-200 uppercase font-['Poppins']">
                                                        ${rec.type}
                                                    </span>
                                                </div>
                                                <h4 class="text-white font-semibold font-['Poppins'] mb-1">${rec.title}</h4>
                                                ${rec.reason ? `
                                                    <p class="text-zinc-400 text-sm font-['Poppins'] italic">"${rec.reason}"</p>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `).join('') : `
                                    <div class="text-center py-12">
                                        <i class="fas fa-robot text-zinc-500 text-3xl mb-3"></i>
                                        <p class="text-zinc-400 font-['Poppins']">AI recommendations will appear as the player progresses</p>
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section - Removed since moved to Performance tab -->
        `;

        // --- 5. RENDER CHART ---
        if (curve.accuracy_trend && curve.accuracy_trend.length > 0) {
            new Chart(document.getElementById("playerCurveChart"), {
                type: "line",
                data: {
                    labels: curve.accuracy_trend.map((_, i) => `Sesi ${i + 1}`),
                    datasets: [
                        {
                            label: "Akurasi (%)",
                            data: curve.accuracy_trend,
                            borderColor: "#4f46e5",
                            backgroundColor: "rgba(79, 70, 229, 0.1)",
                            borderWidth: 2,
                            pointBackgroundColor: "#fff",
                            pointBorderColor: "#4f46e5",
                            pointRadius: 4,
                            fill: true,
                            tension: 0.3,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { borderDash: [2, 4] },
                        },
                        x: { grid: { display: false } },
                    },
                    plugins: { legend: { display: false } },
                },
            });
        } else {
            document.getElementById('playerCurveChart').parentNode.innerHTML =
                '<div class="flex flex-col items-center justify-center h-full text-zinc-400 bg-zinc-700 rounded border border-dashed border-zinc-600"><i class="fa-solid fa-chart-line text-3xl mb-2 opacity-50"></i><span class="text-sm font-[\'Poppins\']">Belum cukup data grafik.</span></div>';
        }
    } catch (e) {
        console.error(e);
        detailWrapper.innerHTML = `
            <div class="bg-red-900/50 border border-red-600 text-red-300 px-4 py-3 rounded relative m-4 shadow">
                <strong class="font-bold font-['Poppins']"><i class="fa-solid fa-triangle-exclamation"></i> Gagal memuat detail!</strong>
                <span class="block sm:inline mt-1 text-sm font-['Poppins']">${e.message}</span>
                <button onclick="renderPlayerList()" class="mt-3 bg-red-600/50 hover:bg-red-600 text-red-300 px-3 py-1 rounded text-xs font-bold font-['Poppins'] transition-colors">Kembali</button>
            </div>
        `;
    }
}

// --- DEBOUNCE SEARCH ---
let timeout;
function handleSearch(val) {
    clearTimeout(timeout);
    timeout = setTimeout(() => renderPlayerList(val), 500);
}

// --- BAN PLAYER FUNCTION ---
let currentPlayerToBan = null;

function banPlayer(playerId) {
    // Cari nama player untuk ditampilkan di modal
    const playerCard = document.querySelector(`[data-player-id="${playerId}"]`);
    const playerName = playerCard ? playerCard.getAttribute('data-player-name') : 'Player';

    currentPlayerToBan = playerId;
    document.getElementById('ban-player-name').textContent = `Player: ${playerName}`;

    const modal = document.getElementById('ban-modal');
    modal.classList.remove('hidden');
    modal.querySelector('.bg-zinc-800').classList.add('modal-enter');
}

function closeBanModal() {
    const modal = document.getElementById('ban-modal');
    const modalContent = modal.querySelector('.bg-zinc-800');

    modalContent.classList.remove('modal-enter');
    modalContent.classList.add('modal-exit');

    setTimeout(() => {
        modal.classList.add('hidden');
        modalContent.classList.remove('modal-exit');
        currentPlayerToBan = null;
    }, 300);
}

async function confirmBanPlayer() {
    if (!currentPlayerToBan) return;

    // Show loading
    showLoading('Memblokir player...');
    closeBanModal();

    try {
        const response = await fetch(`${BASE_API}/players/${currentPlayerToBan}/ban`, {
            method: 'POST',
            headers: headers
        });

        hideLoading();

        if (response.ok) {
            showNotification('Player berhasil diblokir', 'success');
            renderPlayerList(); // Refresh the list
        } else {
            const error = await response.json();
            showNotification(error.message || 'Gagal memblokir player', 'error');
        }
    } catch (e) {
        hideLoading();
        showNotification('Error: ' + e.message, 'error');
    }

    currentPlayerToBan = null;
}

// --- DELETE PLAYER FUNCTION ---
let currentPlayerToDelete = null;

function deletePlayer(playerId) {
    // Cari nama player untuk ditampilkan di modal
    const playerCard = document.querySelector(`[data-player-id="${playerId}"]`);
    const playerName = playerCard ? playerCard.getAttribute('data-player-name') : 'Player';

    currentPlayerToDelete = playerId;
    document.getElementById('delete-player-name').textContent = `Player: ${playerName}`;

    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    modal.querySelector('.bg-zinc-800').classList.add('modal-enter');
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    const modalContent = modal.querySelector('.bg-zinc-800');

    modalContent.classList.remove('modal-enter');
    modalContent.classList.add('modal-exit');

    setTimeout(() => {
        modal.classList.add('hidden');
        modalContent.classList.remove('modal-exit');
        currentPlayerToDelete = null;
    }, 300);
}

async function confirmDeletePlayer() {
    if (!currentPlayerToDelete) return;

    // Show loading
    showLoading('Menghapus player...');
    closeDeleteModal();

    try {
        const response = await fetch(`${BASE_API}/players/${currentPlayerToDelete}`, {
            method: 'DELETE',
            headers: headers
        });

        hideLoading();

        if (response.ok) {
            showNotification('Player berhasil dihapus', 'success');
            renderPlayerList(); // Refresh the list
        } else {
            const error = await response.json();
            showNotification(error.message || 'Gagal menghapus player', 'error');
        }
    } catch (e) {
        hideLoading();
        showNotification('Error: ' + e.message, 'error');
    }

    currentPlayerToDelete = null;
}

// --- EXPORT PLAYER DATA FUNCTION ---
async function exportPlayerData(playerId) {
    showLoading('Mengexport data player...');

    try {
        const response = await fetch(`${BASE_API}/players/${playerId}/export`, {
            method: 'GET',
            headers: headers
        });

        hideLoading();

        if (response.ok) {
            // Create download link
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `player_${playerId}_data.json`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            showNotification('Data player berhasil diexport', 'success');
        } else {
            const error = await response.json();
            showNotification(error.message || 'Gagal mengexport data player', 'error');
        }
    } catch (e) {
        hideLoading();
        showNotification('Error: ' + e.message, 'error');
    }
}

// --- TAB SWITCHING FUNCTION ---
function switchTab(tabName) {
    // Remove active class from all tabs and panels
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-green-600', 'text-white');
        btn.classList.add('text-zinc-400', 'hover:text-white', 'hover:bg-zinc-700');
    });

    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
        panel.classList.remove('active');
    });

    // Add active class to selected tab and panel
    const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
    const activePanel = document.querySelector(`[data-panel="${tabName}"]`);

    if (activeTab && activePanel) {
        activeTab.classList.add('active', 'bg-green-600', 'text-white');
        activeTab.classList.remove('text-zinc-400', 'hover:text-white', 'hover:bg-zinc-700');

        activePanel.classList.remove('hidden');
        activePanel.classList.add('active');
    }
}

// --- UTILITY FUNCTIONS ---
function showLoading(text = 'Memproses...') {
    document.getElementById('loading-text').textContent = text;
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0 max-w-sm`;

    if (type === 'success') {
        notification.className += ' bg-green-600 text-white';
        notification.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-3"></i><span class="font-['Poppins']">${message}</span></div>`;
    } else if (type === 'error') {
        notification.className += ' bg-red-600 text-white';
        notification.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-triangle mr-3"></i><span class="font-['Poppins']">${message}</span></div>`;
    } else {
        notification.className += ' bg-blue-600 text-white';
        notification.innerHTML = `<div class="flex items-center"><i class="fas fa-info-circle mr-3"></i><span class="font-['Poppins']">${message}</span></div>`;
    }

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    }, 100);

    // Hide after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
