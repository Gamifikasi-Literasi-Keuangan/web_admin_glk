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
            <div class="hidden lg:grid grid-cols-12 gap-4 mb-4 px-4 md:px-6 py-3 md:py-4 bg-zinc-300 rounded-xl md:rounded-2xl border border-zinc-400">
                <div class="col-span-4 text-gray-800 text-base md:text-lg font-semibold font-['Poppins']">Player Name</div>
                <div class="col-span-3 text-gray-800 text-base md:text-lg font-semibold font-['Poppins']">Cluster AI</div>
                <div class="col-span-2 text-gray-800 text-base md:text-lg font-semibold font-['Poppins']">Status</div>
                <div class="col-span-3 text-gray-800 text-base md:text-lg font-semibold font-['Poppins'] text-center">Aksi</div>
            </div>
            
            <div class="space-y-3 md:space-y-4">
        `;

        players.forEach((p, index) => {
            // Handle different status values - banned, inactive, or active
            let statusColor, statusText;
            if (p.status === 'Active' || p.status === 'active') {
                statusColor = 'bg-green-600/75';
                statusText = 'Active';
            } else if (p.status === 'Banned' || p.status === 'banned') {
                statusColor = 'bg-red-600/75';
                statusText = 'Banned';
            } else {
                statusColor = 'bg-gray-600/75';
                statusText = 'Deactivated';
            }

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
                                ${(p.status === 'Banned' || p.status === 'banned') ?
                    `<button onclick="unbanPlayer('${p.player_id}')" class="bg-green-600/70 hover:bg-green-600 text-white p-2 rounded-lg transition-colors action-button" title="Unban Player">
                                        <i class="fas fa-user-check text-sm"></i>
                                    </button>` :
                    `<button onclick="banPlayer('${p.player_id}')" class="bg-red-600/70 hover:bg-red-600 text-white p-2 rounded-lg transition-colors action-button" title="Ban Player">
                                        <i class="fas fa-ban text-sm"></i>
                                    </button>`
                }
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
                            ${(p.status === 'Banned' || p.status === 'banned') ?
                    `<button onclick="unbanPlayer('${p.player_id}')" class="bg-green-600/70 hover:bg-green-600 text-white p-2 rounded-lg transition-colors action-button" title="Unban Player">
                                    <i class="fas fa-user-check text-lg"></i>
                                </button>` :
                    `<button onclick="banPlayer('${p.player_id}')" class="bg-red-600/70 hover:bg-red-600 text-white p-2 rounded-lg transition-colors action-button" title="Ban Player">
                                    <i class="fas fa-ban text-lg"></i>
                                </button>`
                }
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
        // FETCH 4 API SEKALIGUS dengan error handling yang lebih baik
        const [resDetail, resAnalysis, resCurve, resSkill] = await Promise.allSettled([
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

        // Handle API responses with proper error checking
        let detail = null, analysis = null, curve = null, skill = null;

        // Process detail response (required)
        if (resDetail.status === 'fulfilled' && resDetail.value.ok) {
            detail = await resDetail.value.json();
        } else {
            throw new Error('Player data not found');
        }

        // Process analysis response (optional)
        if (resAnalysis.status === 'fulfilled' && resAnalysis.value.ok) {
            analysis = await resAnalysis.value.json();
        } else {
            analysis = { weaknesses: [], recommendations: [] };
            console.warn('Analysis data not available for player:', playerId);
        }

        // Process learning curve response (optional)
        if (resCurve.status === 'fulfilled' && resCurve.value.ok) {
            curve = await resCurve.value.json();
        } else {
            curve = { accuracy_trend: [] };
            console.warn('Learning curve data not available for player:', playerId);
        }

        // Process skill matrix response (optional)
        if (resSkill.status === 'fulfilled' && resSkill.value.ok) {
            skill = await resSkill.value.json();
        } else {
            skill = {};
            console.warn('Skill matrix data not available for player:', playerId);
        }

        // Extract data with multiple fallback options
        const playerData = detail.data || detail.player_info || detail.player || detail;
        const p = playerData.player_info || playerData;

        // Handle AI profile with multiple possible structures
        const aiData = playerData.ai_profile || playerData.ai_data || playerData.profile || {};
        const ai = {
            cluster: aiData.cluster || aiData.ai_cluster || aiData.type || 'Unprofiled',
            ai_confidence: aiData.ai_confidence || aiData.confidence || aiData.accuracy || '0%',
            traits: aiData.traits || aiData.characteristics || aiData.tags || [],
            initial_answers: aiData.initial_answers || aiData.profiling_answers || aiData.answers || []
        };

        // Handle stats with multiple possible field names
        const statsData = playerData.lifetime_stats || playerData.statistics || playerData.stats || {};
        const stats = {
            total_games: statsData.total_games || statsData.games_played || statsData.game_count || 0,
            avg_score: statsData.avg_score || statsData.average_score || statsData.mean_score || 0,
            win_rate: statsData.win_rate || statsData.wins_percentage || statsData.success_rate || '0%',
            total_playtime: statsData.total_playtime || statsData.playtime || statsData.time_played || '0h'
        };

        // Handle analysis data
        const analysisData = analysis.data || analysis;
        const weaknesses = analysisData.weaknesses || analysisData.weak_areas || analysisData.improvements || [];
        const recommendations = analysisData.recommendations || analysisData.suggestions || analysisData.advice || [];

        // Handle player basic info with fallbacks
        const playerInfo = {
            player_id: p.player_id || p.id || p.user_id || playerId,
            name: p.name || p.player_name || p.username || p.display_name || 'Unknown Player',
            username: p.username || p.user_name || p.login || 'unknown',
            join_date: p.join_date || p.created_at || p.registration_date || p.signup_date || '',
            status: p.status || p.player_status || p.state || 'Active'
        };

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
            <div class="mt-6 p-4 bg-zinc-400/30 rounded-xl border border-zinc-600/50">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-clipboard-list text-blue-600"></i>
                    <span class="text-sm font-bold text-black uppercase font-['Poppins']">Profiling Awal</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    ${answers.map((ans, idx) => {
            const question = profilingMap[idx] || {};
            const answerData = question[ans] || { text: ans, class: 'bg-gray-100 text-gray-800' };
            return `
                            <div class="text-center p-3 rounded-lg border ${answerData.class || 'bg-zinc-100 border-zinc-300'} transition-all hover:scale-105">
                                <div class="text-xs uppercase opacity-70 font-['Poppins'] mb-2">Question ${idx + 1}</div>
                                <div class="font-bold text-sm font-['Poppins']">${answerData.text || ans}</div>
                            </div>
                        `;
        })
                .join("")}
                </div>
            </div>
        ` : `
            <div class="mt-6 p-4 bg-zinc-400/30 rounded-xl border border-zinc-600/50 text-center">
                <i class="fas fa-info-circle text-gray-500 mb-2"></i>
                <p class="text-sm text-gray-600 italic font-['Poppins']">Belum ada data profiling awal</p>
            </div>
        `;

        // --- 3. GENERATE HTML SKILL MATRIX ---
        const skillHtml = Object.entries(skill).length > 0 ? Object.entries(skill).map(([k, v]) => {
            // Handle different skill data formats from API
            const skillName = k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

            // Handle multiple value formats: string levels, numeric scores, objects
            let skillLevel, skillColor, skillIcon;

            if (typeof v === 'object' && v !== null) {
                // Handle object format: {score: 4, level: 'Expert'} or {value: 85}
                skillLevel = v.level || v.name || 'Unknown';
                const numericValue = v.score || v.value || v.points || 0;

                if (numericValue >= 80 || skillLevel.toLowerCase().includes('expert')) {
                    skillColor = 'bg-green-500';
                    skillIcon = 'fas fa-star';
                } else if (numericValue >= 60 || skillLevel.toLowerCase().includes('intermediate')) {
                    skillColor = 'bg-yellow-500';
                    skillIcon = 'fas fa-check-circle';
                } else {
                    skillColor = 'bg-red-500';
                    skillIcon = 'fas fa-times-circle';
                }
            } else if (typeof v === 'string') {
                // Handle string format: 'Expert', 'Intermediate', 'Beginner'
                skillLevel = v;
                if (v.toLowerCase().includes('expert') || v.toLowerCase().includes('advanced')) {
                    skillColor = 'bg-green-500';
                    skillIcon = 'fas fa-star';
                } else if (v.toLowerCase().includes('intermediate') || v.toLowerCase().includes('medium')) {
                    skillColor = 'bg-yellow-500';
                    skillIcon = 'fas fa-check-circle';
                } else {
                    skillColor = 'bg-red-500';
                    skillIcon = 'fas fa-times-circle';
                }
            } else {
                // Handle numeric format: 0-5 scale or 0-100 scale
                const numericValue = Number(v) || 0;
                if (numericValue <= 5) {
                    // 0-5 scale
                    skillLevel = numericValue >= 4 ? 'Expert' : numericValue >= 3 ? 'Intermediate' : 'Beginner';
                    skillColor = numericValue >= 4 ? 'bg-green-500' : numericValue >= 3 ? 'bg-yellow-500' : 'bg-red-500';
                } else {
                    // 0-100 scale  
                    skillLevel = numericValue >= 80 ? 'Expert' : numericValue >= 60 ? 'Intermediate' : 'Beginner';
                    skillColor = numericValue >= 80 ? 'bg-green-500' : numericValue >= 60 ? 'bg-yellow-500' : 'bg-red-500';
                }
                skillIcon = skillColor === 'bg-green-500' ? 'fas fa-star' :
                    skillColor === 'bg-yellow-500' ? 'fas fa-check-circle' : 'fas fa-times-circle';
            }

            return `
                <div class="bg-zinc-400/30 border border-zinc-600/50 rounded-lg p-4 hover:bg-zinc-400/40 transition-all">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="${skillColor} w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="${skillIcon} text-white text-sm"></i>
                            </div>
                            <span class="text-black font-medium font-['Poppins']">${skillName}</span>
                        </div>
                        <div class="${skillColor} px-3 py-1 rounded-full">
                            <span class="text-white text-xs font-bold uppercase font-['Poppins']">${skillLevel}</span>
                        </div>
                    </div>
                </div>
            `;
        }).join('') : `
            <div class="bg-zinc-400/30 border border-zinc-600/50 rounded-lg p-4 text-center">
                <i class="fas fa-chart-bar text-gray-500 text-xl mb-2"></i>
                <p class="text-gray-600 text-sm font-['Poppins']">Belum ada data skill matrix</p>
            </div>
        `;

        // --- 4. BUILD HTML UTAMA ---
        detailWrapper.innerHTML = `
            <!-- Back Button -->
            <div class="mb-4 md:mb-6">
            <button onclick="renderPlayerList()" class="flex items-center gap-2 md:gap-3 text-gray-600 hover:text-black font-medium font-['Poppins'] transition-colors group">
                <div class="bg-zinc-400 hover:bg-zinc-500 p-2 rounded-lg transition-colors group-hover:scale-105">
                <i class="fa-solid fa-arrow-left text-base md:text-lg"></i>
                </div>
                <span class="text-base md:text-lg">Kembali ke Daftar Player</span>
            </button>
            </div>

            <!-- Player Header Card -->
            <div class="bg-zinc-300 border border-zinc-400 rounded-xl md:rounded-2xl p-4 md:p-6 mb-4 md:mb-6 shadow-lg">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-4 md:gap-6">
                <!-- Player Basic Info -->
                <div class="flex-1 w-full">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 md:gap-4 mb-4">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-lg md:text-2xl"></i>
                    </div>
                    <div class="flex-1">
                    <h1 class="text-xl md:text-3xl font-bold text-black font-['Poppins']">${p.name}</h1>
                    <p class="text-gray-600 text-base md:text-lg font-['Poppins']">@${p.username}</p>
                    <p class="text-gray-700 text-xs md:text-sm font-['Poppins']">Bergabung: ${p.join_date?.substring(0, 10)}</p>
                    </div>
                </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2 md:gap-3 w-full lg:w-auto">
                    ${(p.status === 'Banned' || p.status === 'banned') ?
                `<button onclick="unbanPlayer('${p.player_id || ''}')" class="bg-green-600 hover:bg-green-700 text-white px-3 md:px-4 py-2 rounded-lg font-semibold font-['Poppins'] transition-all action-button flex items-center gap-2 text-sm md:text-base" title="Unban Player">
                        <i class="fas fa-user-check"></i>
                        <span class="hidden sm:inline">Unban</span>
                        </button>` :
                `<button onclick="banPlayer('${p.player_id || ''}')" class="bg-red-600 hover:bg-red-700 text-white px-3 md:px-4 py-2 rounded-lg font-semibold font-['Poppins'] transition-all action-button flex items-center gap-2 text-sm md:text-base" title="Ban Player">
                        <i class="fas fa-ban"></i>
                        <span class="hidden sm:inline">Ban</span>
                        </button>`
            }
                    <button onclick="deletePlayer('${p.player_id || ''}')" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 md:px-4 py-2 rounded-lg font-semibold font-['Poppins'] transition-all action-button flex items-center gap-2 text-sm md:text-base" title="Delete Player">
                        <i class="fas fa-trash"></i>
                        <span class="hidden sm:inline">Delete</span>
                    </button>
                    <button onclick="exportPlayerData('${p.player_id || ''}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-lg font-semibold font-['Poppins'] transition-all action-button flex items-center gap-2 text-sm md:text-base" title="Export Data">
                        <i class="fas fa-download"></i>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                    </div>
                </div>
                </div>
                
                ${profilingHtml}
                </div>
                
                <!-- AI Cluster Info -->
                <div class="bg-gradient-to-r from-green-600/20 to-green-700/20 border border-green-500/30 p-4 md:p-6 rounded-xl lg:min-w-[280px]">
                <div class="text-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-brain text-white text-lg md:text-xl"></i>
                    </div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide font-['Poppins'] mb-1">AI Cluster</p>
                    <h3 class="text-xl md:text-2xl font-bold text-green-600 font-['Poppins'] mb-2">${ai.cluster || 'Unprofiled'}</h3>
                    <p class="text-xs md:text-sm text-gray-700 font-['Poppins'] mb-4">Confidence: ${ai.ai_confidence || '0%'}</p>
                    
                    <!-- AI Traits -->
                    <div class="flex flex-wrap justify-center gap-1 md:gap-2">
                    ${ai.traits ? ai.traits.map(t => `
                        <span class="inline-block bg-zinc-400/50 border border-zinc-500 text-black text-xs px-2 md:px-3 py-1 rounded-full font-['Poppins']">
                        ${t}
                        </span>
                    `).join('') : '<span class="text-gray-600 text-xs md:text-sm font-[\'Poppins\']">No traits data</span>'}
                    </div>
                </div>
                </div>
            </div>
            </div>

            <!-- Stats & Analysis Grid -->
            <div class="mb-4 md:mb-6">
            <!-- Tab Navigation -->
            <div class="flex flex-col sm:flex-row gap-2 mb-4 md:mb-6 bg-zinc-400 border border-zinc-500 rounded-xl p-2">
                <button onclick="switchTab('overview')" class="tab-btn active flex-1 px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-all text-gray-700 text-sm md:text-base" data-tab="overview">
                <i class="fas fa-chart-bar mr-1 md:mr-2"></i><span class="hidden sm:inline">Overview</span><span class="sm:hidden">Stats</span>
                </button>
                <button onclick="switchTab('performance')" class="tab-btn flex-1 px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-all text-gray-700 text-sm md:text-base" data-tab="performance">
                <i class="fas fa-trophy mr-1 md:mr-2"></i><span class="hidden sm:inline">Performance</span><span class="sm:hidden">Perf</span>
                </button>
                <button onclick="switchTab('analysis')" class="tab-btn flex-1 px-3 md:px-4 py-2 md:py-3 rounded-lg font-semibold font-['Poppins'] transition-all text-gray-700 text-sm md:text-base" data-tab="analysis">
                <i class="fas fa-brain mr-1 md:mr-2"></i><span class="hidden sm:inline">Analysis</span><span class="sm:hidden">AI</span>
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                
                <!-- Overview Tab -->
                <div class="tab-panel active grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-6" data-panel="overview">
                
                <!-- Game Statistics -->
                <div class="bg-zinc-300 border border-zinc-400 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-gamepad text-white text-sm md:text-base"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-black font-['Poppins']">Game Statistics</h3>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
                    <div class="bg-gradient-to-r from-blue-600/20 to-blue-700/20 border border-blue-500/30 rounded-lg md:rounded-xl p-3 md:p-4 text-center">
                        <div class="text-lg md:text-2xl font-bold text-blue-400 font-['Poppins'] mb-1">${stats.total_games || 0}</div>
                        <div class="text-xs text-black uppercase font-['Poppins']">Total Games</div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-600/20 to-purple-700/20 border border-purple-500/30 rounded-lg md:rounded-xl p-3 md:p-4 text-center">
                        <div class="text-lg md:text-2xl font-bold text-purple-400 font-['Poppins'] mb-1">${stats.avg_score || 0}</div>
                        <div class="text-xs text-black uppercase font-['Poppins']">Avg Score</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-600/20 to-green-700/20 border border-green-500/30 rounded-lg md:rounded-xl p-3 md:p-4 text-center">
                        <div class="text-lg md:text-2xl font-bold text-green-400 font-['Poppins'] mb-1">${stats.win_rate || '0%'}</div>
                        <div class="text-xs text-black uppercase font-['Poppins']">Win Rate</div>
                    </div>
                    <div class="bg-gradient-to-r from-orange-600/20 to-orange-700/20 border border-orange-500/30 rounded-lg md:rounded-xl p-3 md:p-4 text-center">
                        <div class="text-lg md:text-2xl font-bold text-orange-400 font-['Poppins'] mb-1">${stats.total_playtime || '0h'}</div>
                        <div class="text-xs text-black uppercase font-['Poppins']">Playtime</div>
                    </div>
                    </div>
                </div>

                <!-- AI Cluster Info Extended -->
                <div class="bg-zinc-300 border border-zinc-400 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-brain text-white text-sm md:text-base"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-black font-['Poppins']">AI Analysis</h3>
                    </div>
                    
                    <div class="text-center mb-4 md:mb-6">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                        <i class="fas fa-user-tie text-white text-lg md:text-2xl"></i>
                    </div>
                    <h4 class="text-lg md:text-2xl font-bold text-green-600 font-['Poppins'] mb-2">${ai.cluster || 'Unprofiled'}</h4>
                    <div class="bg-zinc-400/50 rounded-lg p-3 mb-3 md:mb-4">
                        <p class="text-xs md:text-sm text-black font-['Poppins']">Confidence Level</p>
                        <div class="flex items-center gap-2 md:gap-3 mt-2">
                        <div class="flex-1 bg-zinc-500 rounded-full h-2 md:h-3">
                            <div class="bg-green-500 h-2 md:h-3 rounded-full" style="width: ${ai.ai_confidence || '0%'}"></div>
                        </div>
                        <span class="text-green-600 font-bold font-['Poppins'] text-xs md:text-sm">${ai.ai_confidence || '0%'}</span>
                        </div>
                    </div>
                    </div>
                    
                    <!-- AI Traits -->
                    <div class="space-y-2">
                    <h5 class="text-xs md:text-sm font-bold text-black mb-2 md:mb-3 font-['Poppins']">Player Traits:</h5>
                    <div class="flex flex-wrap gap-1 md:gap-2">
                        ${ai.traits ? ai.traits.map(t => `
                        <span class="bg-gradient-to-r from-purple-600/20 to-purple-700/20 border border-purple-500/30 text-purple-800 text-xs px-2 md:px-3 py-1 md:py-2 rounded-full font-['Poppins']">
                            ${t}
                        </span>
                        `).join('') : '<span class="text-gray-600 text-xs md:text-sm font-[\'Poppins\']">No traits identified yet</span>'}
                    </div>
                    </div>
                </div>
                </div>

                <!-- Performance Tab -->
                <div class="tab-panel grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-6 hidden" data-panel="performance">
                
                <!-- Learning Curve Chart -->
                <div class="xl:col-span-2 bg-zinc-300 border border-zinc-400 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 md:gap-4 mb-4 md:mb-6">
                    <div class="flex items-center gap-2 md:gap-3">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-sm md:text-base"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-black font-['Poppins']">Learning Progress</h3>
                    </div>
                    <span class="bg-zinc-400 px-2 md:px-3 py-1 rounded-lg text-xs text-gray-700 font-['Poppins'] self-start sm:self-center">
                        Accuracy Trend
                    </span>
                    </div>
                    <div class="h-64 md:h-80 bg-zinc-900/50 rounded-lg p-2 md:p-4">
                    <canvas id="playerCurveChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <!-- Skill Matrix -->
                <div class="bg-zinc-300 border border-zinc-400 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cogs text-white text-sm md:text-base"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-black font-['Poppins']">Skills</h3>
                    </div>
                    
                    <div class="space-y-2 md:space-y-3 max-h-64 md:max-h-80 overflow-y-auto custom-scrollbar">
                    ${skillHtml || `
                        <div class="text-center py-8 md:py-12">
                        <i class="fas fa-chart-bar text-zinc-500 text-2xl md:text-3xl mb-2 md:mb-3"></i>
                        <p class="text-zinc-400 text-xs md:text-sm font-['Poppins']">Skill data will appear after more games</p>
                        </div>
                    `}
                    </div>
                </div>
                </div>

                <!-- Analysis Tab -->
                <div class="tab-panel grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-6 hidden" data-panel="analysis">
                
                <!-- Weakness Analysis -->
                <div class="bg-zinc-300 border border-zinc-400 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-sm md:text-base"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-black font-['Poppins']">Areas for Improvement</h3>
                    </div>
                    
                    <div class="space-y-2 md:space-y-3 max-h-64 md:max-h-80 overflow-y-auto custom-scrollbar">
                    ${weaknesses.length > 0 ? weaknesses.map((w, index) => `
                        <div class="bg-gradient-to-r from-red-900/30 to-red-800/30 border border-red-600/30 rounded-lg md:rounded-xl p-3 md:p-4 hover:from-red-900/40 hover:to-red-800/40 transition-all">
                        <div class="flex items-center gap-2 md:gap-3 mb-2">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">${index + 1}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                            <h4 class="text-red-300 font-semibold font-['Poppins'] text-sm md:text-base truncate">${w.category}</h4>
                            </div>
                            <div class="bg-red-600/50 px-2 md:px-3 py-1 rounded-full flex-shrink-0">
                            <span class="text-white text-xs font-bold font-['Poppins']">${w.accuracy}</span>
                            </div>
                        </div>
                        ${w.description ? `<p class="text-zinc-400 text-xs md:text-sm font-['Poppins'] ml-8 md:ml-11">${w.description}</p>` : ''}
                        </div>
                    `).join('') : `
                        <div class="text-center py-8 md:py-12">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                            <i class="fas fa-check text-green-400 text-lg md:text-2xl"></i>
                        </div>
                        <h4 class="text-green-400 font-semibold font-['Poppins'] mb-2 text-sm md:text-base">Great Performance!</h4>
                        <p class="text-zinc-400 font-['Poppins'] text-xs md:text-sm">No significant weaknesses detected</p>
                        </div>
                    `}
                    </div>
                </div>

                <!-- AI Recommendations -->
                <div class="bg-zinc-300 border border-zinc-400 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
                    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lightbulb text-white text-sm md:text-base"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-black font-['Poppins']">AI Recommendations</h3>
                    </div>
                    
                    <div class="space-y-3 md:space-y-4 max-h-64 md:max-h-80 overflow-y-auto custom-scrollbar">
                    ${recommendations.length > 0 ? recommendations.map((rec, index) => `
                        <div class="bg-gradient-to-r from-purple-600/20 to-purple-700/20 border border-purple-500/30 rounded-lg md:rounded-xl p-3 md:p-4 hover:from-purple-600/30 hover:to-purple-700/30 transition-all duration-200">
                        <div class="flex items-start gap-2 md:gap-3">
                            <div class="w-6 h-6 md:w-8 md:h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-white text-xs font-bold font-['Poppins']">${index + 1}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-purple-500/50 px-2 py-1 rounded text-xs font-bold text-purple-200 uppercase font-['Poppins']">
                                ${rec.type}
                                </span>
                            </div>
                            <h4 class="text-black font-semibold font-['Poppins'] mb-1 text-sm md:text-base">${rec.title}</h4>
                            ${rec.reason ? `
                                <p class="text-gray-600 text-xs md:text-sm font-['Poppins'] italic">"${rec.reason}"</p>
                            ` : ''}
                            </div>
                        </div>
                        </div>
                    `).join('') : `
                        <div class="text-center py-8 md:py-12">
                        <i class="fas fa-robot text-zinc-500 text-2xl md:text-3xl mb-2 md:mb-3"></i>
                        <p class="text-zinc-400 font-['Poppins'] text-xs md:text-sm">AI recommendations will appear as the player progresses</p>
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
                '<div class="flex flex-col items-center justify-center h-full text-gray-600 bg-zinc-300 rounded border border-dashed border-zinc-400"><i class="fa-solid fa-chart-line text-3xl mb-2 opacity-50"></i><span class="text-sm font-[\'Poppins\']">Belum cukup data grafik.</span></div>';
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

// --- UNBAN PLAYER FUNCTION ---
let currentPlayerToUnban = null;

function unbanPlayer(playerId) {
    // Cari nama player untuk ditampilkan di modal
    const playerCard = document.querySelector(`[data-player-id="${playerId}"]`);
    const playerName = playerCard ? playerCard.getAttribute('data-player-name') : 'Player';

    currentPlayerToUnban = playerId;
    document.getElementById('unban-player-name').textContent = `Player: ${playerName}`;

    const modal = document.getElementById('unban-modal');
    modal.classList.remove('hidden');
    modal.querySelector('.bg-zinc-800').classList.add('modal-enter');
}

function closeUnbanModal() {
    const modal = document.getElementById('unban-modal');
    const modalContent = modal.querySelector('.bg-zinc-800');

    modalContent.classList.remove('modal-enter');
    modalContent.classList.add('modal-exit');

    setTimeout(() => {
        modal.classList.add('hidden');
        modalContent.classList.remove('modal-exit');
        currentPlayerToUnban = null;
    }, 300);
}

async function confirmUnbanPlayer() {
    if (!currentPlayerToUnban) return;

    // Show loading
    showLoading('Membatalkan ban player...');
    closeUnbanModal();

    try {
        const response = await fetch(`${BASE_API}/players/${currentPlayerToUnban}/unban`, {
            method: 'POST',
            headers: {
                ...headers,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: 'active',
                reason: 'Unbanned by admin'
            })
        });

        hideLoading();

        if (response.ok) {
            showNotification('Player berhasil di-unban', 'success');
            // Refresh the current view
            if (document.getElementById('player-container').classList.contains('hidden')) {
                // If we're in detail view, refresh the detail
                renderPlayerDetail(currentPlayerToUnban);
            } else {
                // If we're in list view, refresh the list
                const searchInput = document.getElementById('searchInput');
                renderPlayerList(searchInput ? searchInput.value : '');
            }
        } else {
            const error = await response.json();
            showNotification(error.message || 'Gagal membatalkan ban player', 'error');
        }
    } catch (e) {
        hideLoading();
        showNotification('Error: ' + e.message, 'error');
        console.error('Unban player error:', e);
    }

    currentPlayerToUnban = null;
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
            headers: {
                ...headers,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: 'banned',
                reason: 'Banned by admin'
            })
        });

        hideLoading();

        if (response.ok) {
            showNotification('Player berhasil diblokir', 'success');
            // Refresh the current view
            if (document.getElementById('player-container').classList.contains('hidden')) {
                // If we're in detail view, refresh the detail
                renderPlayerDetail(currentPlayerToBan);
            } else {
                // If we're in list view, refresh the list
                const searchInput = document.getElementById('searchInput');
                renderPlayerList(searchInput ? searchInput.value : '');
            }
        } else {
            const error = await response.json();
            showNotification(error.message || 'Gagal memblokir player', 'error');
        }
    } catch (e) {
        hideLoading();
        showNotification('Error: ' + e.message, 'error');
        console.error('Ban player error:', e);
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
            headers: {
                ...headers,
                'Content-Type': 'application/json'
            }
        });

        hideLoading();

        if (response.ok) {
            showNotification('Player berhasil dihapus', 'success');

            // If we're in detail view, go back to list view
            if (document.getElementById('player-container').classList.contains('hidden')) {
                renderPlayerList();
            } else {
                // If we're in list view, refresh the list
                const searchInput = document.getElementById('searchInput');
                renderPlayerList(searchInput ? searchInput.value : '');
            }
        } else {
            const error = await response.json();
            showNotification(error.message || 'Gagal menghapus player', 'error');
        }
    } catch (e) {
        hideLoading();
        showNotification('Error: ' + e.message, 'error');
        console.error('Delete player error:', e);
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
        btn.classList.add('text-gray-600', 'hover:text-black', 'hover:bg-zinc-400');
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
        activeTab.classList.remove('text-gray-600', 'hover:text-black', 'hover:bg-zinc-400');
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
