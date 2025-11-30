const headers = {
    'Authorization': `Bearer ${token}`, // token diambil dari layout utama
    'Accept': 'application/json'
};

// --- INIT ---
document.addEventListener('DOMContentLoaded', () => {
    renderPlayerList();
});

// --- 1. RENDER LIST PLAYERS ---
async function renderPlayerList(keyword = '') {
    const wrapper = document.getElementById('table-wrapper');
    const detailWrapper = document.getElementById('detail-wrapper');
    const container = document.getElementById('player-container');

    // Tampilkan container list, sembunyikan detail
    container.classList.remove('hidden');
    detailWrapper.classList.add('hidden');
    
    if(!keyword) wrapper.innerHTML = '<div class="loader"></div>';

    try {
        const url = `${BASE_API}/players?limit=20&search=${encodeURIComponent(keyword)}`;
        const response = await fetch(url, { headers });
        
        if (response.status === 401) {
            wrapper.innerHTML = `<div class="bg-red-100 text-red-700 p-4 rounded">Sesi habis. Silakan login ulang.</div>`;
            return;
        }

        const json = await response.json();
        const players = json.data || [];

        if (players.length === 0) {
            wrapper.innerHTML = `<div class="bg-white p-8 text-center text-gray-500 rounded shadow">Tidak ditemukan data pemain.</div>`;
            return;
        }

        let html = `
            <div class="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pemain</th>
                            <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cluster AI</th>
                            <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        players.forEach(p => {
            html += `
                <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="text-gray-900 font-bold whitespace-no-wrap">${p.name}</p>
                                <p class="text-gray-500 text-xs">@${p.username}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full text-xs">
                            ${p.cluster}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 font-semibold leading-tight ${p.status === 'Active' ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100'} rounded-full text-xs">
                            ${p.status}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <button onclick="renderPlayerDetail('${p.player_id}')" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold border border-indigo-200 px-3 py-1 rounded hover:bg-indigo-50">
                            Detail
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `</tbody></table></div>`;
        wrapper.innerHTML = html;

        // Restore focus input search agar UX nyaman
        const input = document.getElementById('searchInput');
        if(input) { input.focus(); input.value = keyword; }

    } catch (e) {
        wrapper.innerHTML = `<div class="text-red-500 p-4">Error: ${e.message}</div>`;
    }
}

// --- 2. RENDER DETAIL PLAYER (FULL VERSION WITH PROFILING) ---
async function renderPlayerDetail(playerId) {
    const container = document.getElementById('player-container');
    const detailWrapper = document.getElementById('detail-wrapper');
    
    // Switch View ke Detail
    container.classList.add('hidden');
    detailWrapper.classList.remove('hidden');
    detailWrapper.innerHTML = '<div class="loader"></div>';

    try {
        // FETCH 4 API SEKALIGUS
        const [resDetail, resAnalysis, resCurve, resSkill] = await Promise.all([
            fetch(`${BASE_API}/players/${playerId}`, { headers }),
            fetch(`${BASE_API}/players/${playerId}/analysis`, { headers }),
            fetch(`${BASE_API}/analytics/learning-curve?player_id=${playerId}`, { headers }),
            fetch(`${BASE_API}/analytics/skill-matrix?player_id=${playerId}`, { headers })
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
            0: { // Gaji Pertama
                'A': { text: '50% Tabungan', class: 'bg-green-100 text-green-800' },
                'B': { text: 'Seimbang', class: 'bg-blue-100 text-blue-800' },
                'C': { text: 'Gaya Hidup', class: 'bg-red-100 text-red-800' },
                'D': { text: 'Investasi', class: 'bg-orange-100 text-orange-800' }
            },
            1: { // Modal Bisnis
                'A': { text: 'Tolak (Aman)', class: 'bg-green-100 text-green-800' },
                'B': { text: 'Ambil Pinjol', class: 'bg-red-100 text-red-800' },
                'C': { text: 'Pinjam Ortu', class: 'bg-yellow-100 text-yellow-800' },
                'D': { text: 'Pakai Tabungan', class: 'bg-orange-100 text-orange-800' }
            },
            2: { // Crypto FOMO
                'A': { text: 'Riset Dulu', class: 'bg-green-100 text-green-800' },
                'B': { text: 'All-in (FOMO)', class: 'bg-red-100 text-red-800' },
                'C': { text: 'Ikut Teman', class: 'bg-yellow-100 text-yellow-800' },
                'D': { text: 'Tidak Tertarik', class: 'bg-gray-100 text-gray-800' }
            }
        };

        // --- 2. GENERATE HTML PROFILING ---
        const answers = Array.isArray(ai.initial_answers) 
    ? ai.initial_answers 
    : []; 
        const profilingHtml = answers.length > 0 ? `
            <div class="mt-4 pt-4 border-t border-gray-100">
                <span class="text-xs font-bold text-gray-500 uppercase block mb-2"><i class="fa-solid fa-clipboard-list mr-1"></i> Profiling Awal:</span>
                <div class="grid grid-cols-3 gap-2">
                    ${answers.map((ans, idx) => {
                        const map = profilingMap[idx] ? profilingMap[idx][ans] : { text: ans, class: 'bg-gray-100 text-gray-600' };
                        return `
                            <div class="text-center p-2 rounded border ${map.class || 'bg-gray-50'}">
                                <div class="text-[10px] uppercase opacity-70">Q${idx + 1}</div>
                                <div class="font-bold text-xs mt-1 whitespace-nowrap overflow-hidden text-ellipsis">${map.text || ans}</div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        ` : '<p class="text-xs text-gray-400 mt-2 italic">Belum ada data profiling.</p>';

        // --- 3. GENERATE HTML SKILL MATRIX ---
        const skillHtml = Object.entries(skill).map(([k, v]) => `
            <div class="flex justify-between items-center bg-gray-50 p-2 rounded mb-1 border border-gray-100">
                <span class="text-sm font-medium text-gray-700">${k}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-white uppercase ${v==='Expert'?'bg-green-500':(v==='Intermediate'?'bg-yellow-500':'bg-red-500')}">
                    ${v}
                </span>
            </div>
        `).join('');

        // --- 4. BUILD HTML UTAMA ---
        detailWrapper.innerHTML = `
            <button onclick="renderPlayerList()" class="mb-4 text-gray-600 hover:text-gray-900 flex items-center gap-2 font-medium transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </button>

            <div class="bg-white shadow rounded-lg p-6 mb-6 border-l-4 border-indigo-500 flex flex-col md:flex-row justify-between items-start gap-4">
                <div class="flex-1 w-full">
                    <h1 class="text-2xl font-bold text-gray-800">${p.name}</h1>
                    <p class="text-gray-500 text-sm">@${p.username} • Joined: ${p.join_date?.substring(0,10)}</p>
                    
                    ${profilingHtml}
                </div>
                
                <div class="text-left md:text-right w-full md:w-auto bg-indigo-50 md:bg-transparent p-3 md:p-0 rounded">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Cluster AI</p>
                    <span class="text-lg font-bold text-indigo-600">${ai.cluster || 'Unprofiled'}</span>
                    <p class="text-xs text-gray-400">Confidence: ${ai.ai_confidence}</p>
                    <div class="mt-2 flex flex-wrap justify-end gap-1">
                        ${ai.traits ? ai.traits.map(t => `<span class="inline-block bg-white border border-gray-200 text-gray-600 text-[10px] px-2 py-0.5 rounded shadow-sm">${t}</span>`).join('') : ''}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold mb-4 border-b pb-2">Statistik Permainan</h3>
                    <div class="grid grid-cols-3 gap-2 text-center mb-4">
                        <div class="p-2 bg-gray-50 rounded border border-gray-100">
                            <span class="block text-xl font-bold text-gray-800">${stats.total_games || 0}</span>
                            <span class="text-[10px] text-gray-500 uppercase">Games</span>
                        </div>
                        <div class="p-2 bg-gray-50 rounded border border-gray-100">
                            <span class="block text-xl font-bold text-gray-800">${stats.avg_score || 0}</span>
                            <span class="text-[10px] text-gray-500 uppercase">Avg Score</span>
                        </div>
                        <div class="p-2 bg-gray-50 rounded border border-gray-100">
                            <span class="block text-xl font-bold text-green-600">${stats.win_rate || '0%'}</span>
                            <span class="text-[10px] text-gray-500 uppercase">Win Rate</span>
                        </div>
                    </div>
                    
                    <h4 class="text-sm font-bold text-gray-600 mb-2">Diagnosis Kelemahan:</h4>
                    <ul class="space-y-2 max-h-40 overflow-y-auto pr-1 custom-scrollbar">
                        ${weaknesses.length > 0 ? weaknesses.map(w => `
                            <li class="text-sm text-red-600 bg-red-50 px-3 py-2 rounded flex justify-between border border-red-100">
                                <span>${w.category}</span>
                                <span class="font-bold text-xs bg-white px-2 py-0.5 rounded border border-red-200">Acc: ${w.accuracy}</span>
                            </li>
                        `).join('') : '<li class="text-sm text-green-600 bg-green-50 p-2 rounded"><i class="fa-solid fa-check-circle mr-1"></i> Tidak ada kelemahan signifikan.</li>'}
                    </ul>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-robot text-indigo-500"></i> Rekomendasi AI
                    </h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                        ${recommendations.length > 0 ? recommendations.map(rec => `
                            <div class="p-3 bg-indigo-50 rounded border border-indigo-100 hover:bg-indigo-100 transition">
                                <div class="text-[10px] font-bold text-indigo-600 uppercase mb-1">${rec.type}</div>
                                <div class="text-sm font-semibold text-gray-800">${rec.title}</div>
                                ${rec.reason ? `<div class="text-xs text-gray-500 mt-1 italic">"${rec.reason}"</div>` : ''}
                            </div>
                        `).join('') : '<p class="text-sm text-gray-500 italic">Belum ada rekomendasi khusus saat ini.</p>'}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white shadow p-6 rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700">📈 Kurva Pembelajaran</h3>
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">Akurasi per Sesi</span>
                    </div>
                    <div class="h-64">
                        <canvas id="playerCurveChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow p-6 rounded-lg">
                    <h3 class="font-bold mb-4 text-gray-700">🧠 Skill Matrix</h3>
                    <div class="border rounded p-4 h-64 overflow-y-auto custom-scrollbar bg-gray-50">
                        ${skillHtml || '<div class="flex flex-col items-center justify-center h-full text-gray-400 text-xs"><i class="fa-solid fa-chart-bar text-2xl mb-2"></i>Belum ada data skill</div>'}
                    </div>
                </div>
            </div>
        `;

        // --- 5. RENDER CHART ---
        if (curve.accuracy_trend && curve.accuracy_trend.length > 0) {
            new Chart(document.getElementById('playerCurveChart'), {
                type: 'line',
                data: {
                    labels: curve.accuracy_trend.map((_, i) => `Sesi ${i+1}`),
                    datasets: [{
                        label: 'Akurasi (%)',
                        data: curve.accuracy_trend,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 100, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        } else {
            document.getElementById('playerCurveChart').parentNode.innerHTML = 
                '<div class="flex flex-col items-center justify-center h-full text-gray-400 bg-gray-50 rounded border border-dashed border-gray-300"><i class="fa-solid fa-chart-line text-3xl mb-2 opacity-50"></i><span class="text-sm">Belum cukup data grafik.</span></div>';
        }

    } catch (e) {
        console.error(e);
        detailWrapper.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4 shadow">
            <strong class="font-bold"><i class="fa-solid fa-triangle-exclamation"></i> Gagal memuat detail!</strong>
            <span class="block sm:inline mt-1 text-sm">${e.message}</span>
            <button onclick="renderPlayerList()" class="mt-3 bg-red-200 hover:bg-red-300 text-red-800 px-3 py-1 rounded text-xs font-bold">Kembali</button>
        </div>`;
    }
}

// --- DEBOUNCE SEARCH ---
let timeout;
function handleSearch(val) {
    clearTimeout(timeout);
    timeout = setTimeout(() => renderPlayerList(val), 500);
}