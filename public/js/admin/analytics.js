const headers = { 
    'Authorization': `Bearer ${token}`, 
    'Accept': 'application/json',
    'Content-Type': 'application/json'
};
// --- Helper functions untuk data safety ---
const safePercent = (val) => val === 0 ? '0%' : (val ? `${Math.round(val)}%` : '-');
const safeNum = (val) => val !== null && val !== undefined ? val : 0;
const safeStr = (val) => val || '-';

let currentTab = 'business';
let charts = {}; 

document.addEventListener('DOMContentLoaded', () => loadData());

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
        btn.classList.add('border-transparent', 'text-zinc-500', 'bg-white');
    });
    document.getElementById(`tab-${tab}`).classList.remove('border-transparent', 'text-zinc-500', 'bg-white');
    document.getElementById(`tab-${tab}`).classList.add('border-green-500', 'text-green-600', 'bg-green-50');
    loadData();
}

async function loadData() {
    const container = document.getElementById('analytics-content');
    container.innerHTML = '<div class="flex items-center justify-center h-64"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500"></div></div>';
    
    Object.values(charts).forEach(c => c.destroy());
    charts = {};

    try {
        if(currentTab === 'business') await renderBusinessTab(container);
        else if(currentTab === 'learning') await renderLearningTab(container);
        else if(currentTab === 'behavior') await renderBehaviorTab(container);
        else if(currentTab === 'content') await renderContentTab(container);
        else await renderVisualTab(container);
    } catch (e) {
        console.error('Analytics Error:', e);
        container.innerHTML = `<div class="text-red-500 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="font-semibold">Error loading data:</div>
            <div class="text-sm">${e.message}</div>
        </div>`;
    }
}

// --- 1. TAB BISNIS (KPI, Growth, Funnel, Engagement) ---
async function renderBusinessTab(container) {
    try {
        const [resKPI, resGrowth, resFunnel, resEngage] = await Promise.all([
            fetch(`${BASE_API}/metrics/kpi`, { headers }),
            fetch(`${BASE_API}/metrics/growth`, { headers }),
            fetch(`${BASE_API}/analytics/funnel`, { headers }),
            fetch(`${BASE_API}/metrics/engagement`, { headers })
        ]);

        // Check if requests were successful
        if (!resKPI.ok) throw new Error(`KPI endpoint failed: ${resKPI.status}`);
        if (!resGrowth.ok) throw new Error(`Growth endpoint failed: ${resGrowth.status}`);
        if (!resFunnel.ok) throw new Error(`Funnel endpoint failed: ${resFunnel.status}`);
        if (!resEngage.ok) throw new Error(`Engagement endpoint failed: ${resEngage.status}`);

        const kpi = await resKPI.json();
        const growth = await resGrowth.json();
        const funnel = await resFunnel.json();
        const engage = await resEngage.json();

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-green-500">
                <p class="text-zinc-500 text-xs uppercase font-medium">Daily Active Users</p>
                <h3 class="text-2xl font-bold text-zinc-800">${kpi.dau}</h3>
            </div>
            <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-green-600">
                <p class="text-zinc-500 text-xs uppercase font-medium">Monthly Active Users</p>
                <h3 class="text-2xl font-bold text-zinc-800">${kpi.mau}</h3>
            </div>
            <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-zinc-400">
                <p class="text-zinc-500 text-xs uppercase font-medium">Avg Session Time</p>
                <h3 class="text-2xl font-bold text-zinc-800">${engage.avg_session_time}</h3>
            </div>
            <div class="bg-white p-5 rounded-lg shadow-md border-l-4 border-zinc-500">
                <p class="text-zinc-500 text-xs uppercase font-medium">Completion Rate</p>
                <h3 class="text-2xl font-bold text-zinc-800">${engage.completion_rate}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-zinc-700 mb-4">Pertumbuhan</h4>
                <div class="h-64">
                    <canvas id="chartGrowth"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-zinc-700 mb-4">Funnel Permainan</h4>
                <div class="h-64">
                    <canvas id="chartFunnel"></canvas>
                </div>
            </div>
        </div>
    `;

    charts.growth = new Chart(document.getElementById('chartGrowth'), {
        type: 'line',
        data: {
            labels: growth.labels,
            datasets: [
                { label: 'Pemain Baru', data: growth.player_growth, borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.1)', tension: 0.3 },
                { label: 'Sesi Game', data: growth.session_growth, borderColor: '#71717a', backgroundColor: 'rgba(113, 113, 122, 0.1)', tension: 0.3 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#71717a'
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#71717a' },
                    grid: { color: '#e4e4e7' }
                },
                y: {
                    ticks: { color: '#71717a' },
                    grid: { color: '#e4e4e7' }
                }
            }
        }
    });

    charts.funnel = new Chart(document.getElementById('chartFunnel'), {
        type: 'bar',
        data: {
            labels: funnel.stages.map(s => s.stage),
            datasets: [{
                label: 'Jumlah User',
                data: funnel.stages.map(s => s.count),
                backgroundColor: ['#10b981', '#16a34a', '#15803d']
            }]
        },
        options: { 
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#71717a'
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#71717a' },
                    grid: { color: '#e4e4e7' }
                },
                y: {
                    ticks: { color: '#71717a' },
                    grid: { color: '#e4e4e7' }
                }
            }
        }
    });
    } catch (error) {
        console.error('Business Tab Error:', error);
        throw error;
    }
}

// --- 2. TAB PEMBELAJARAN (Outcomes, Mastery, Difficulty) ---
async function renderLearningTab(container) {
    try {
        const [resOutcome, resMastery, resDiff] = await Promise.all([
            fetch(`${BASE_API}/reports/outcomes?category=All`, { headers }),
            fetch(`${BASE_API}/analytics/mastery`, { headers }),
            fetch(`${BASE_API}/analytics/difficulty`, { headers })
        ]);

        // Check response status
        if (!resOutcome.ok) throw new Error(`Outcomes endpoint failed: ${resOutcome.status}`);
        if (!resMastery.ok) throw new Error(`Mastery endpoint failed: ${resMastery.status}`);
        if (!resDiff.ok) throw new Error(`Difficulty endpoint failed: ${resDiff.status}`);

        const outcome = await resOutcome.json();
        const mastery = await resMastery.json();
        const difficulty = await resDiff.json();

        // Handle case when no data is available
        const hasData = outcome.students > 0;
        
        // Use sample data if no real data exists
        const pre = hasData ? safeNum(outcome.pre_test_avg) : 45.5;
        const post = hasData ? safeNum(outcome.post_test_avg) : 72.3;
        const rate = hasData ? safeStr(outcome.improvement_rate) : '+59.1%';
        const studentCount = hasData ? outcome.students : 0;

        const hPre = Math.max(pre, 5);
        const hPost = Math.max(post, 5);

    container.innerHTML = `
        ${!hasData ? `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">Tidak Ada Data Pembelajaran</h4>
                    <p class="text-yellow-700 text-sm">Belum ada data keputusan pemain (player_decisions) di database. Data di bawah adalah contoh simulasi.</p>
                </div>
            </div>
        </div>
        ` : ''}
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Card Hasil Belajar -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-zinc-700 mb-4">Hasil Belajar (Pre vs Post)</h4>
                <div class="flex items-end justify-center h-48 gap-12 mb-4 border-b border-zinc-100 pb-4">
                    <!-- BAR PRE-TEST -->
                    <div class="text-center group relative w-16">
                        <div class="bg-zinc-300 w-full rounded-t transition-all duration-500" style="height: ${hPre}%"></div>
                        <p class="font-bold mt-2 text-zinc-700">${pre}${!hasData ? '*' : ''}</p> 
                        <p class="text-xs text-zinc-500 uppercase tracking-wider">Awal</p>
                    </div>

                    <!-- BAR POST-TEST -->
                    <div class="text-center group relative w-16">
                        <div class="bg-green-500 w-full rounded-t transition-all duration-500" style="height: ${hPost}%"></div>
                        <p class="font-bold mt-2 text-green-600">${post}${!hasData ? '*' : ''}</p> 
                        <p class="text-xs text-zinc-500 uppercase tracking-wider">Akhir</p>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-xs text-zinc-400 mb-1">${hasData ? `Data dari ${studentCount} pemain` : 'Tingkat Peningkatan (Simulasi)'}</p>
                    <p class="text-lg font-bold text-green-600 bg-green-50 inline-block px-4 py-1 rounded-full border border-green-200">
                        ${rate} 🚀
                    </p>
                    ${!hasData ? '<p class="text-xs text-yellow-600 mt-2">* Data simulasi</p>' : ''}
                </div>
            </div>

            <!-- Card Penguasaan Materi -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-zinc-700 mb-4">Penguasaan Materi${!hasData ? ' (Simulasi)' : ''}</h4>
                <div class="h-56 flex justify-center items-center">
                    <canvas id="chartMastery"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Analisis Kesulitan -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold text-zinc-700 mb-4 text-red-600">⚠️ Analisis Kesulitan${!hasData ? ' (Simulasi)' : ''}</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="p-3 text-left text-zinc-700">Konten</th>
                            <th class="p-3 text-center text-zinc-700">Akurasi</th>
                            <th class="p-3 text-left text-zinc-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${hasData && difficulty.anomalies.length > 0 ? 
                            difficulty.anomalies.map(d => `
                                <tr class="border-b border-zinc-100">
                                    <td class="p-3 font-medium text-zinc-800">${safeStr(d.title)}</td>
                                    <td class="p-3 text-center font-bold">${safePercent(d.acc)}</td>
                                    <td class="p-3"><span class="px-2 py-1 rounded text-xs text-white ${d.acc < 30 ? 'bg-red-500' : 'bg-green-500'}">${d.acc < 30 ? 'Terlalu Sulit' : 'Terlalu Mudah'}</span></td>
                                </tr>
                            `).join('') 
                            : 
                            `${!hasData ? `
                                <tr class="border-b border-zinc-100">
                                    <td class="p-3 font-medium text-zinc-800">Skenario Investasi Saham</td>
                                    <td class="p-3 text-center font-bold">25%</td>
                                    <td class="p-3"><span class="px-2 py-1 rounded text-xs text-white bg-red-500">Terlalu Sulit</span></td>
                                </tr>
                                <tr class="border-b border-zinc-100">
                                    <td class="p-3 font-medium text-zinc-800">Kuis Bunga Bank</td>
                                    <td class="p-3 text-center font-bold">95%</td>
                                    <td class="p-3"><span class="px-2 py-1 rounded text-xs text-white bg-green-500">Terlalu Mudah</span></td>
                                </tr>
                            ` : '<tr><td colspan="3" class="p-4 text-center text-zinc-500">Semua soal seimbang.</td></tr>'}`
                        }
                    </tbody>
                </table>
            </div>
        </div>
    `;

    // Set chart data - use sample data if no real data
    const masteryData = hasData ? {
        mastered: safeNum(mastery.mastered),
        learning: safeNum(mastery.learning), 
        struggling: safeNum(mastery.struggling)
    } : {
        mastered: 12,
        learning: 8,
        struggling: 3
    };

    charts.mastery = new Chart(document.getElementById('chartMastery'), {
        type: 'doughnut',
        data: {
            labels: ['Mastered', 'Learning', 'Struggling'],
            datasets: [{
                data: [masteryData.mastered, masteryData.learning, masteryData.struggling],
                backgroundColor: ['#10b981', '#71717a', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#71717a',
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const suffix = hasData ? ' pemain' : ' (simulasi)';
                            return label + ': ' + value + suffix;
                        }
                    }
                }
            }
        }
    });
    } catch (error) {
        console.error('Learning Tab Error:', error);
        throw error;
    }
}

// --- 3. TAB PERILAKU ---
async function renderBehaviorTab(container) {
    try {
        const [resAI, resMistakes] = await Promise.all([
            fetch(`${BASE_API}/analytics/interventions`, { headers }),
            fetch(`${BASE_API}/analytics/mistakes`, { headers })
        ]);

        if (!resAI.ok) throw new Error(`Interventions endpoint failed: ${resAI.status}`);
        if (!resMistakes.ok) throw new Error(`Mistakes endpoint failed: ${resMistakes.status}`);

        const ai = await resAI.json();
        const mistakeData = await resMistakes.json();
        const mistakes = mistakeData.mistakes || [];

    container.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-1">
                <h4 class="font-bold text-zinc-700 mb-4">Respon Intervensi AI</h4>
                <div class="h-48 flex justify-center items-center"><canvas id="chartAI"></canvas></div>
                <div class="text-center mt-4">
                    <span class="text-2xl font-bold text-green-600">${safeStr(ai.success_rate)}</span>
                    <p class="text-xs text-zinc-500">Tingkat Kepatuhan</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-2">
                <h4 class="font-bold text-zinc-700 mb-4">Top 5 Kesalahan Umum</h4>
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
                                    <td class="p-2 font-medium text-zinc-800">${safeStr(m.title)}</td>
                                    <td class="p-2 text-zinc-600 italic">"${safeStr(m.text)}"</td>
                                    <td class="p-2 text-right font-bold">${safeNum(m.count)}</td>
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
            datasets: [{ data: [safeNum(ai.heeded), safeNum(ai.ignored)], backgroundColor: ['#10b981', '#71717a'] }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#71717a',
                        padding: 10
                    }
                }
            }
        }
    });
    } catch (error) {
        console.error('Behavior Tab Error:', error);
        throw error;
    }
}

// --- 4. TAB KONTEN (FIXED) ---
async function renderContentTab(container) {
    try {
        const [resScen, resCard, resQuiz] = await Promise.all([
            fetch(`${BASE_API}/analytics/scenarios`, { headers }),
            fetch(`${BASE_API}/analytics/cards`, { headers }),
            fetch(`${BASE_API}/analytics/quizzes`, { headers })
        ]);

        if (!resScen.ok) throw new Error(`Scenarios endpoint failed: ${resScen.status}`);
        if (!resCard.ok) throw new Error(`Cards endpoint failed: ${resCard.status}`);
        if (!resQuiz.ok) throw new Error(`Quizzes endpoint failed: ${resQuiz.status}`);

        const scenarioData = await resScen.json();
        const cardData = await resCard.json();
        const quizData = await resQuiz.json();

        const scenarios = scenarioData.data || [];
        const cards = cardData.data || [];
        const quizzes = quizData.data || [];

        // Helper untuk menangani nilai 0 atau null dengan aman (Nullish Coalescing)
        const getVal = (val) => val !== null && val !== undefined ? val : 0;

    const renderRow = (list) => list.map(i => `
        <tr class="border-b border-zinc-100 hover:bg-green-50">
            <td class="p-2 truncate max-w-xs text-zinc-800" title="${i.title || i.question}">
                ${i.title || i.question}
            </td>
            <td class="p-2 text-center font-bold">
                ${i.acc !== undefined 
                    ? Math.round(i.acc)+'%'  // Tampilkan Akurasi (Scenario/Quiz)
                    : (i.impact ? (i.impact > 0 ? '+'+i.impact : i.impact) : '-')} </td>
            <td class="p-2 text-right text-zinc-600">
                ${(i.usage_count ?? i.freq ?? i.attempts ?? 0)}x
            </td>
        </tr>
    `).join('');

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-green-500">
                <h4 class="font-bold text-zinc-700 border-b border-zinc-100 pb-2 mb-2">Skenario (Akurasi)</h4>
                <table class="w-full text-sm">
                    <thead class="bg-zinc-100 text-xs text-zinc-500">
                        <tr><th class="text-left p-1">Judul</th><th class="p-1">Benar</th><th class="text-right p-1">Main</th></tr>
                    </thead>
                    <tbody>${renderRow(scenarios)}</tbody>
                </table>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-zinc-400">
                <h4 class="font-bold text-zinc-700 border-b border-zinc-100 pb-2 mb-2">Kartu (Dampak Skor)</h4>
                <table class="w-full text-sm">
                    <thead class="bg-zinc-100 text-xs text-zinc-500">
                        <tr><th class="text-left p-1">Kartu</th><th class="p-1">Efek</th><th class="text-right p-1">Muncul</th></tr>
                    </thead>
                    <tbody>${renderRow(cards)}</tbody>
                </table>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md border-t-4 border-green-600">
                <h4 class="font-bold text-zinc-700 border-b border-zinc-100 pb-2 mb-2">Kuis (Akurasi)</h4>
                <table class="w-full text-sm">
                    <thead class="bg-zinc-100 text-xs text-zinc-500">
                        <tr><th class="text-left p-1">Soal</th><th class="p-1">Benar</th><th class="text-right p-1">Jawab</th></tr>
                    </thead>
                    <tbody>${renderRow(quizzes)}</tbody>
                </table>
            </div>
        </div>
    `;
    } catch (error) {
        console.error('Content Tab Error:', error);
        throw error;
    }
}

// --- 5. TAB VISUAL (Heatmap) ---
async function renderVisualTab(container) {
    try {
        const [resScore, resTile, resTime] = await Promise.all([
            fetch(`${BASE_API}/analytics/distribution`, { headers }),
            fetch(`${BASE_API}/analytics/heatmap/tiles`, { headers }),
            fetch(`${BASE_API}/analytics/heatmap/time`, { headers })
        ]);

        if (!resScore.ok) throw new Error(`Distribution endpoint failed: ${resScore.status}`);
        if (!resTile.ok) throw new Error(`Tile heatmap endpoint failed: ${resTile.status}`);
        if (!resTime.ok) throw new Error(`Time heatmap endpoint failed: ${resTime.status}`);

        const dist = await resScore.json();
        const tileData = await resTile.json();
        const timeData = await resTime.json();

        const tiles = tileData.tiles || [];
        const timeGrid = timeData.heatmap || {};

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-zinc-700 mb-4">Distribusi Skor Akhir</h4>
                <div class="h-64">
                    <canvas id="chartDist"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-zinc-700 mb-4">Heatmap Kunjungan Tile</h4>
                <div class="overflow-y-auto max-h-64 border border-zinc-200 rounded">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-100"><tr><th class="p-2 text-zinc-700">ID</th><th class="p-2 text-zinc-700">Hits</th><th class="p-2 text-zinc-700">Visual</th></tr></thead>
                        <tbody>
                            ${tiles.map(t => `<tr>
                                <td class="p-2 font-mono text-zinc-600">${t.tile_id}</td>
                                <td class="p-2 text-right text-zinc-800 font-medium">${t.visits}</td>
                                <td class="p-2"><div class="h-2 bg-zinc-200 rounded"><div class="h-full bg-green-500" style="width:${Math.min(t.visits/10*100,100)}%"></div></div></td>
                            </tr>`).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h4 class="font-bold text-zinc-700 mb-4">Heatmap Waktu Main (Jam Sibuk)</h4>
            <div class="grid grid-cols-12 gap-1 text-[10px]">
                ${Array.from({length:24}, (_,i) => `<div class="text-center text-zinc-400">${i}</div>`).join('')}
                ${Object.keys(timeGrid).map(day => `
                    <div class="col-span-12 grid grid-cols-12 gap-1 mb-1">
                        <div class="col-span-12 font-bold text-xs mb-1 text-zinc-700">${day}</div>
                        ${Array.from({length:24}, (_,h) => {
                            const val = timeGrid[day][h] || 0;
                            const color = val > 10 ? 'bg-green-600' : (val > 5 ? 'bg-green-400' : (val > 0 ? 'bg-green-200' : 'bg-zinc-100'));
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
            datasets: [{ label: 'Jumlah Pemain', data: Object.values(dist.distribution), backgroundColor: '#10b981' }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#71717a'
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#71717a' },
                    grid: { color: '#e4e4e7' }
                },
                y: {
                    ticks: { color: '#71717a' },
                    grid: { color: '#e4e4e7' }
                }
            }
        }
    });
    } catch (error) {
        console.error('Visual Tab Error:', error);
        throw error;
    }
}