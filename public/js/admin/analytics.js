const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };
// --- TAMBAHKAN 2 BARIS INI DI SINI ---
const safePercent = (val) => val === 0 ? '0%' : (val ? `${Math.round(val)}%` : '-');
const safeNum = (val) => val !== null && val !== undefined ? val : 0;
let currentTab = 'business';
let charts = {}; 

document.addEventListener('DOMContentLoaded', () => loadData());

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

async function loadData() {
    const container = document.getElementById('analytics-content');
    container.innerHTML = '<div class="loader"></div>';
    
    Object.values(charts).forEach(c => c.destroy());
    charts = {};

    try {
        if(currentTab === 'business') await renderBusinessTab(container);
        else if(currentTab === 'learning') await renderLearningTab(container);
        else if(currentTab === 'behavior') await renderBehaviorTab(container);
        else if(currentTab === 'content') await renderContentTab(container);
        else await renderVisualTab(container);
    } catch (e) {
        container.innerHTML = `<div class="text-red-500 p-4">Error: ${e.message}</div>`;
    }
}

// --- 1. TAB BISNIS (KPI, Growth, Funnel, Engagement) ---
async function renderBusinessTab(container) {
    const [resKPI, resGrowth, resFunnel, resEngage] = await Promise.all([
        fetch(`${BASE_API}/metrics/kpi`, { headers }),
        fetch(`${BASE_API}/metrics/growth`, { headers }),
        fetch(`${BASE_API}/analytics/funnel`, { headers }),
        fetch(`${BASE_API}/metrics/engagement`, { headers })
    ]);

    const kpi = await resKPI.json();
    const growth = await resGrowth.json();
    const funnel = await resFunnel.json();
    const engage = await resEngage.json();

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-500">
                <p class="text-gray-500 text-xs uppercase">Daily Active Users</p>
                <h3 class="text-2xl font-bold text-gray-800">${kpi.dau}</h3>
            </div>
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-indigo-500">
                <p class="text-gray-500 text-xs uppercase">Monthly Active Users</p>
                <h3 class="text-2xl font-bold text-gray-800">${kpi.mau}</h3>
            </div>
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
                <p class="text-gray-500 text-xs uppercase">Avg Session Time</p>
                <h3 class="text-2xl font-bold text-gray-800">${engage.avg_session_time}</h3>
            </div>
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-purple-500">
                <p class="text-gray-500 text-xs uppercase">Completion Rate</p>
                <h3 class="text-2xl font-bold text-gray-800">${engage.completion_rate}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold text-gray-700 mb-4">Pertumbuhan</h4>
                <canvas id="chartGrowth"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold text-gray-700 mb-4">Funnel Permainan</h4>
                <canvas id="chartFunnel"></canvas>
            </div>
        </div>
    `;

    charts.growth = new Chart(document.getElementById('chartGrowth'), {
        type: 'line',
        data: {
            labels: growth.labels,
            datasets: [
                { label: 'Pemain Baru', data: growth.player_growth, borderColor: '#4f46e5', tension: 0.3 },
                { label: 'Sesi Game', data: growth.session_growth, borderColor: '#10b981', tension: 0.3 }
            ]
        }
    });

    charts.funnel = new Chart(document.getElementById('chartFunnel'), {
        type: 'bar',
        data: {
            labels: funnel.stages.map(s => s.stage),
            datasets: [{
                label: 'Jumlah User',
                data: funnel.stages.map(s => s.count),
                backgroundColor: ['#6366f1', '#8b5cf6', '#d946ef']
            }]
        },
        options: { indexAxis: 'y' }
    });
}

// --- 2. TAB PEMBELAJARAN (Outcomes, Mastery, Difficulty) ---
async function renderLearningTab(container) {
    const [resOutcome, resMastery, resDiff] = await Promise.all([
        fetch(`${BASE_API}/reports/outcomes?category=All`, { headers }).then(r => r.json()),
        fetch(`${BASE_API}/analytics/mastery`, { headers }).then(r => r.json()),
        fetch(`${BASE_API}/analytics/difficulty`, { headers }).then(r => r.json())
    ]);

    const pre = resOutcome.pre_test_avg ?? 0;
    const post = resOutcome.post_test_avg ?? 0;
    const rate = resOutcome.improvement_rate ?? '0%';

    const hPre = Math.max(pre, 5);
    const hPost = Math.max(post, 5);

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Card Hasil Belajar -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold text-gray-700 mb-4">Hasil Belajar (Pre vs Post)</h4>
                <div class="flex items-end justify-center h-48 gap-12 mb-4 border-b border-gray-100 pb-4">
                    <!-- BAR PRE-TEST -->
                    <div class="text-center group relative w-16">
                        <div class="bg-gray-300 w-full rounded-t transition-all duration-500" style="height: ${hPre}%"></div>
                        <p class="font-bold mt-2 text-gray-700">${pre}</p> 
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Awal</p>
                    </div>

                    <!-- BAR POST-TEST -->
                    <div class="text-center group relative w-16">
                        <div class="bg-green-500 w-full rounded-t transition-all duration-500" style="height: ${hPost}%"></div>
                        <p class="font-bold mt-2 text-green-600">${post}</p> 
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Akhir</p>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Tingkat Peningkatan</p>
                    <p class="text-lg font-bold text-indigo-600 bg-indigo-50 inline-block px-4 py-1 rounded-full">
                        ${rate} 🚀
                    </p>
                </div>
            </div>

            <!-- Card Penguasaan Materi -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold text-gray-700 mb-4">Penguasaan Materi</h4>
                <div class="h-64 flex justify-center">
                    <canvas id="chartMastery"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Analisis Kesulitan -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="font-bold text-gray-700 mb-4 text-red-600">⚠️ Analisis Kesulitan (Soal Anomali)</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Konten</th>
                            <th class="p-3 text-center">Akurasi</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${resDiff.anomalies.length > 0 ? resDiff.anomalies.map(d => `
                            <tr class="border-b">
                                <td class="p-3 font-medium">${d.title}</td>
                                <td class="p-3 text-center font-bold">${safePercent(d.acc)}</td>
                                <td class="p-3"><span class="px-2 py-1 rounded text-xs text-white ${d.acc < 30 ? 'bg-red-500' : 'bg-green-500'}">${d.acc < 30 ? 'Terlalu Sulit' : 'Terlalu Mudah'}</span></td>
                            </tr>
                        `).join('') : '<tr><td colspan="3" class="p-4 text-center text-gray-500">Semua soal seimbang.</td></tr>'}
                    </tbody>
                </table>
            </div>
        </div>
    `;

    charts.mastery = new Chart(document.getElementById('chartMastery'), {
        type: 'doughnut',
        data: {
            labels: ['Mastered', 'Learning', 'Struggling'],
            datasets: [{
                data: [resMastery.mastered, resMastery.learning, resMastery.struggling],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
            }]
        }
    });
}

// --- 3. TAB PERILAKU ---
async function renderBehaviorTab(container) {
    const [resAI, resMistakes] = await Promise.all([
        fetch(`${BASE_API}/analytics/interventions`, { headers }),
        fetch(`${BASE_API}/analytics/mistakes`, { headers })
    ]);

    const ai = await resAI.json();
    const mistakes = await resMistakes.json().then(j => j.mistakes);

    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow lg:col-span-1">
                <h4 class="font-bold text-gray-700 mb-4">Respon Intervensi AI</h4>
                <div class="h-64 flex justify-center"><canvas id="chartAI"></canvas></div>
                <div class="text-center mt-4">
                    <span class="text-2xl font-bold text-blue-600">${ai.success_rate}</span>
                    <p class="text-xs text-gray-500">Tingkat Kepatuhan</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                <h4 class="font-bold text-gray-700 mb-4">Top 5 Kesalahan Umum</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-red-50 text-red-700">
                            <tr>
                                <th class="p-2 text-left">Skenario</th>
                                <th class="p-2 text-left">Jawaban Salah</th>
                                <th class="p-2 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            ${mistakes.map(m => `
                                <tr>
                                    <td class="p-2 font-medium">${m.title}</td>
                                    <td class="p-2 text-gray-600 italic">"${m.text}"</td>
                                    <td class="p-2 text-right font-bold">${m.count}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;

    charts.ai = new Chart(document.getElementById('chartAI'), {
        type: 'pie',
        data: {
            labels: ['Dipatuhi', 'Diabaikan'],
            datasets: [{ data: [ai.heeded, ai.ignored], backgroundColor: ['#3b82f6', '#9ca3af'] }]
        }
    });
}

// --- 4. TAB KONTEN (FIXED) ---
async function renderContentTab(container) {
    const [resScen, resCard, resQuiz] = await Promise.all([
        fetch(`${BASE_API}/analytics/scenarios`, { headers }),
        fetch(`${BASE_API}/analytics/cards`, { headers }),
        fetch(`${BASE_API}/analytics/quizzes`, { headers })
    ]);

    const scenarios = await resScen.json().then(j => j.data);
    const cards = await resCard.json().then(j => j.data);
    const quizzes = await resQuiz.json().then(j => j.data);

    // Helper untuk menangani nilai 0 atau null dengan aman (Nullish Coalescing)
    const getVal = (val) => val !== null && val !== undefined ? val : 0;

    const renderRow = (list) => list.map(i => `
        <tr class="border-b hover:bg-gray-50">
            <td class="p-2 truncate max-w-xs" title="${i.title || i.question}">
                ${i.title || i.question}
            </td>
            <td class="p-2 text-center font-bold">
                ${i.acc !== undefined 
                    ? Math.round(i.acc)+'%'  // Tampilkan Akurasi (Scenario/Quiz)
                    : (i.impact ? (i.impact > 0 ? '+'+i.impact : i.impact) : '-')} </td>
            <td class="p-2 text-right text-gray-600">
                ${(i.usage_count ?? i.freq ?? i.attempts ?? 0)}x
            </td>
        </tr>
    `).join('');

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow border-t-4 border-blue-500">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">Skenario (Akurasi)</h4>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-xs text-gray-500">
                        <tr><th class="text-left p-1">Judul</th><th class="p-1">Benar</th><th class="text-right p-1">Main</th></tr>
                    </thead>
                    <tbody>${renderRow(scenarios)}</tbody>
                </table>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-t-4 border-green-500">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">Kartu (Dampak Skor)</h4>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-xs text-gray-500">
                        <tr><th class="text-left p-1">Kartu</th><th class="p-1">Efek</th><th class="text-right p-1">Muncul</th></tr>
                    </thead>
                    <tbody>${renderRow(cards)}</tbody>
                </table>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-t-4 border-yellow-500">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">Kuis (Akurasi)</h4>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-xs text-gray-500">
                        <tr><th class="text-left p-1">Soal</th><th class="p-1">Benar</th><th class="text-right p-1">Jawab</th></tr>
                    </thead>
                    <tbody>${renderRow(quizzes)}</tbody>
                </table>
            </div>
        </div>
    `;
}

// --- 5. TAB VISUAL (Heatmap) ---
async function renderVisualTab(container) {
    const [resScore, resTile, resTime] = await Promise.all([
        fetch(`${BASE_API}/analytics/distribution`, { headers }),
        fetch(`${BASE_API}/analytics/heatmap/tiles`, { headers }),
        fetch(`${BASE_API}/analytics/heatmap/time`, { headers })
    ]);

    const dist = await resScore.json();
    const tiles = await resTile.json().then(j => j.tiles);
    const timeGrid = await resTime.json().then(j => j.heatmap);

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold text-gray-700 mb-4">Distribusi Skor Akhir</h4>
                <canvas id="chartDist"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold text-gray-700 mb-4">Heatmap Kunjungan Tile</h4>
                <div class="overflow-y-auto max-h-64 border rounded">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100"><tr><th class="p-2">ID</th><th class="p-2">Hits</th><th class="p-2">Visual</th></tr></thead>
                        <tbody>
                            ${tiles.map(t => `<tr>
                                <td class="p-2 font-mono">${t.tile_id}</td>
                                <td class="p-2 text-right">${t.visits}</td>
                                <td class="p-2"><div class="h-2 bg-gray-200 rounded"><div class="h-full bg-red-500" style="width:${Math.min(t.visits/10*100,100)}%"></div></div></td>
                            </tr>`).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="font-bold text-gray-700 mb-4">Heatmap Waktu Main (Jam Sibuk)</h4>
            <div class="grid grid-cols-12 gap-1 text-[10px]">
                ${Array.from({length:24}, (_,i) => `<div class="text-center text-gray-400">${i}</div>`).join('')}
                ${Object.keys(timeGrid).map(day => `
                    <div class="col-span-12 grid grid-cols-12 gap-1 mb-1">
                        <div class="col-span-12 font-bold text-xs mb-1">${day}</div>
                        ${Array.from({length:24}, (_,h) => {
                            const val = timeGrid[day][h] || 0;
                            const color = val > 10 ? 'bg-red-600' : (val > 5 ? 'bg-red-400' : (val > 0 ? 'bg-red-200' : 'bg-gray-100'));
                            return `<div class="h-6 rounded ${color}" title="${val} pemain"></div>`;
                        }).join('')}
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    charts.dist = new Chart(document.getElementById('chartDist'), {
        type: 'bar',
        data: {
            labels: Object.keys(dist.distribution),
            datasets: [{ label: 'Jumlah Pemain', data: Object.values(dist.distribution), backgroundColor: '#f97316' }]
        }
    });
}