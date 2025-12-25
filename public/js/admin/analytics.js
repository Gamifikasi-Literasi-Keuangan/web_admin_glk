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
        <!-- Warning Banner (jika tidak ada data) -->
        ${!hasData ? `
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-xl p-5 mb-6 shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mt-1"></i>
                </div>
                <div class="ml-4">
                    <h4 class="font-bold text-yellow-800 text-lg mb-1">Tidak Ada Data Pembelajaran</h4>
                    <p class="text-yellow-700 text-sm leading-relaxed">
                        Belum ada data keputusan pemain (player_decisions) di database. Data di bawah adalah contoh simulasi.
                    </p>
                </div>
            </div>
        </div>
        ` : ''}
        
        <!-- Summary Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-zinc-50 to-zinc-100 p-5 rounded-xl shadow-sm border border-zinc-200 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-500 text-xs uppercase font-semibold tracking-wide mb-1">Pre-Test</p>
                        <h3 class="text-3xl font-bold text-zinc-700">${pre}${!hasData ? '*' : ''}</h3>
                        <p class="text-xs text-zinc-400 mt-1">Nilai Awal</p>
                    </div>
                    <div class="bg-zinc-200 p-3 rounded-lg">
                        <i class="fas fa-clipboard-list text-zinc-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-5 rounded-xl shadow-sm border border-green-200 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-xs uppercase font-semibold tracking-wide mb-1">Post-Test</p>
                        <h3 class="text-3xl font-bold text-green-700">${post}${!hasData ? '*' : ''}</h3>
                        <p class="text-xs text-green-500 mt-1">Nilai Akhir</p>
                    </div>
                    <div class="bg-green-200 p-3 rounded-lg">
                        <i class="fas fa-chart-line text-green-700 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-100 to-green-200 p-5 rounded-xl shadow-sm border border-green-300 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-700 text-xs uppercase font-semibold tracking-wide mb-1">Peningkatan</p>
                        <h3 class="text-3xl font-bold text-green-800">${rate}</h3>
                        <p class="text-xs text-green-600 mt-1">Improvement</p>
                    </div>
                    <div class="bg-green-300 p-3 rounded-lg">
                        <i class="fas fa-arrow-trend-up text-green-800 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-zinc-50 to-slate-100 p-5 rounded-xl shadow-sm border border-zinc-200 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-zinc-500 text-xs uppercase font-semibold tracking-wide mb-1">Total Pemain</p>
                        <h3 class="text-3xl font-bold text-zinc-700">${studentCount}${!hasData ? '*' : ''}</h3>
                        <p class="text-xs text-zinc-400 mt-1">Participants</p>
                    </div>
                    <div class="bg-zinc-200 p-3 rounded-lg">
                        <i class="fas fa-users text-zinc-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Card Hasil Belajar (Pre vs Post) -->
            <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-md border border-zinc-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5">
                    <h4 class="font-bold text-zinc-700 text-lg flex items-center">
                        <i class="fas fa-chart-bar text-green-500 mr-2"></i>
                        Hasil Belajar
                    </h4>
                    ${!hasData ? '<span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Simulasi</span>' : ''}
                </div>
                
                <!-- Bar Chart Container -->
                <div class="bg-gradient-to-b from-zinc-50 to-white rounded-lg p-4 mb-4">
                    <div class="flex items-end justify-center h-56 gap-16">
                        <!-- BAR PRE-TEST -->
                        <div class="text-center group relative flex-1 max-w-[80px]">
                            <div class="relative h-48 bg-zinc-100 rounded-lg overflow-hidden">
                                <div class="absolute bottom-0 w-full bg-gradient-to-t from-zinc-400 to-zinc-300 rounded-t-lg transition-all duration-700 ease-out shadow-inner" 
                                     style="height: ${hPre}%">
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white drop-shadow-lg">${pre}</span>
                                </div>
                            </div>
                            <p class="font-semibold mt-3 text-zinc-600 text-sm">PRE-TEST</p>
                            <p class="text-xs text-zinc-400 uppercase tracking-wider">Awal</p>
                        </div>

                        <!-- Arrow Icon -->
                        <div class="mb-8 text-green-500 animate-pulse">
                            <i class="fas fa-arrow-right text-3xl"></i>
                        </div>

                        <!-- BAR POST-TEST -->
                        <div class="text-center group relative flex-1 max-w-[80px]">
                            <div class="relative h-48 bg-green-50 rounded-lg overflow-hidden">
                                <div class="absolute bottom-0 w-full bg-gradient-to-t from-green-600 to-green-400 rounded-t-lg transition-all duration-700 ease-out shadow-lg" 
                                     style="height: ${hPost}%">
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white drop-shadow-lg">${post}</span>
                                </div>
                            </div>
                            <p class="font-semibold mt-3 text-green-600 text-sm">POST-TEST</p>
                            <p class="text-xs text-green-500 uppercase tracking-wider">Akhir</p>
                        </div>
                    </div>
                </div>
                
                <!-- Improvement Badge -->
                <div class="text-center bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                    <p class="text-xs text-zinc-500 mb-2 font-medium uppercase tracking-wide">Tingkat Peningkatan</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-2xl font-bold text-green-600">${rate}</span>
                        <span class="text-2xl">🚀</span>
                    </div>
                    ${!hasData ? '<p class="text-xs text-yellow-600 mt-2 font-medium">* Data simulasi</p>' : 
                                 `<p class="text-xs text-zinc-400 mt-2">Dari ${studentCount} pemain</p>`}
                </div>
            </div>

            <!-- Card Penguasaan Materi -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-zinc-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5">
                    <h4 class="font-bold text-zinc-700 text-lg flex items-center">
                        <i class="fas fa-graduation-cap text-green-500 mr-2"></i>
                        Penguasaan Materi
                    </h4>
                    ${!hasData ? '<span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Simulasi</span>' : ''}
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Chart Section -->
                    <div class="flex justify-center items-center bg-gradient-to-br from-zinc-50 to-slate-50 rounded-lg p-4">
                        <div class="h-64 w-full max-w-[280px]">
                            <canvas id="chartMastery"></canvas>
                        </div>
                    </div>
                    
                    <!-- Stats Section -->
                    <div class="flex flex-col justify-center space-y-4">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border-l-4 border-green-500 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-700 font-semibold uppercase tracking-wide mb-1">Mastered</p>
                                    <h3 class="text-3xl font-bold text-green-700">${hasData ? safeNum(mastery.mastered) : 12}</h3>
                                    <p class="text-xs text-green-600 mt-1">Sudah Menguasai</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-zinc-50 to-slate-50 p-4 rounded-lg border-l-4 border-zinc-400 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-zinc-600 font-semibold uppercase tracking-wide mb-1">Learning</p>
                                    <h3 class="text-3xl font-bold text-zinc-700">${hasData ? safeNum(mastery.learning) : 8}</h3>
                                    <p class="text-xs text-zinc-500 mt-1">Sedang Belajar</p>
                                </div>
                                <div class="bg-zinc-200 p-3 rounded-full">
                                    <i class="fas fa-book-reader text-zinc-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-red-50 to-orange-50 p-4 rounded-lg border-l-4 border-red-400 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-red-700 font-semibold uppercase tracking-wide mb-1">Struggling</p>
                                    <h3 class="text-3xl font-bold text-red-700">${hasData ? safeNum(mastery.struggling) : 3}</h3>
                                    <p class="text-xs text-red-600 mt-1">Perlu Bantuan</p>
                                </div>
                                <div class="bg-red-100 p-3 rounded-full">
                                    <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Analisis Kesulitan -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-zinc-100 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-5">
                <h4 class="font-bold text-zinc-700 text-lg flex items-center">
                    <i class="fas fa-triangle-exclamation text-orange-500 mr-2"></i>
                    Analisis Kesulitan Konten
                </h4>
                ${!hasData ? '<span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Simulasi</span>' : ''}
            </div>
            
            <div class="overflow-x-auto rounded-lg border border-zinc-200">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-zinc-100 to-slate-100">
                        <tr>
                            <th class="p-4 text-left text-zinc-700 font-bold uppercase tracking-wide text-xs">
                                <i class="fas fa-file-alt mr-2 text-zinc-500"></i>Konten
                            </th>
                            <th class="p-4 text-center text-zinc-700 font-bold uppercase tracking-wide text-xs">
                                <i class="fas fa-percentage mr-2 text-zinc-500"></i>Akurasi
                            </th>
                            <th class="p-4 text-center text-zinc-700 font-bold uppercase tracking-wide text-xs">
                                <i class="fas fa-signal mr-2 text-zinc-500"></i>Tingkat Kesulitan
                            </th>
                            <th class="p-4 text-left text-zinc-700 font-bold uppercase tracking-wide text-xs">
                                <i class="fas fa-flag mr-2 text-zinc-500"></i>Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-100">
                        ${hasData && difficulty.anomalies.length > 0 ? 
                            difficulty.anomalies.map((d, index) => `
                                <tr class="hover:bg-zinc-50 transition-colors duration-150">
                                    <td class="p-4 font-medium text-zinc-800">
                                        <div class="flex items-center">
                                            <span class="bg-zinc-100 text-zinc-600 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mr-3">${index + 1}</span>
                                            ${safeStr(d.title)}
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="text-lg font-bold ${d.acc < 30 ? 'text-red-600' : d.acc > 80 ? 'text-green-600' : 'text-zinc-600'}">${safePercent(d.acc)}</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="w-32 h-2 bg-zinc-200 rounded-full overflow-hidden">
                                                <div class="h-full ${d.acc < 30 ? 'bg-red-500' : d.acc > 80 ? 'bg-green-500' : 'bg-yellow-500'} transition-all duration-500" 
                                                     style="width: ${d.acc}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold ${
                                            d.acc < 30 
                                                ? 'bg-red-100 text-red-700 border border-red-300' 
                                                : 'bg-green-100 text-green-700 border border-green-300'
                                        }">
                                            <i class="fas ${d.acc < 30 ? 'fa-arrow-up' : 'fa-arrow-down'} mr-1.5"></i>
                                            ${d.acc < 30 ? 'Terlalu Sulit' : 'Terlalu Mudah'}
                                        </span>
                                    </td>
                                </tr>
                            `).join('') 
                            : 
                            `${!hasData ? `
                                <tr class="hover:bg-zinc-50 transition-colors duration-150">
                                    <td class="p-4 font-medium text-zinc-800">
                                        <div class="flex items-center">
                                            <span class="bg-zinc-100 text-zinc-600 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mr-3">1</span>
                                            Skenario Investasi Saham
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="text-lg font-bold text-red-600">25%</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="w-32 h-2 bg-zinc-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-red-500 transition-all duration-500" style="width: 25%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-300">
                                            <i class="fas fa-arrow-up mr-1.5"></i>Terlalu Sulit
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-zinc-50 transition-colors duration-150">
                                    <td class="p-4 font-medium text-zinc-800">
                                        <div class="flex items-center">
                                            <span class="bg-zinc-100 text-zinc-600 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mr-3">2</span>
                                            Kuis Bunga Bank
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="text-lg font-bold text-green-600">95%</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="w-32 h-2 bg-zinc-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-green-500 transition-all duration-500" style="width: 95%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-300">
                                            <i class="fas fa-arrow-down mr-1.5"></i>Terlalu Mudah
                                        </span>
                                    </td>
                                </tr>
                            ` : '<tr><td colspan="4" class="p-6 text-center text-zinc-500 bg-zinc-50"><i class="fas fa-check-circle text-green-500 mr-2"></i>Semua soal memiliki tingkat kesulitan yang seimbang.</td></tr>'}`
                        }
                    </tbody>
                </table>
            </div>
            
            <!-- Info Footer -->
            <div class="mt-4 bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div class="text-xs text-blue-700">
                        <p class="font-semibold mb-1">Interpretasi:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-600">
                            <li><strong>Akurasi &lt; 30%</strong>: Konten terlalu sulit, pertimbangkan untuk menyederhanakan atau memberikan lebih banyak petunjuk.</li>
                            <li><strong>Akurasi &gt; 80%</strong>: Konten terlalu mudah, pertimbangkan untuk meningkatkan kompleksitas.</li>
                            <li><strong>Akurasi 30-80%</strong>: Tingkat kesulitan optimal untuk pembelajaran efektif.</li>
                        </ul>
                    </div>
                </div>
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
                backgroundColor: ['#10b981', '#71717a', '#ef4444'],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 15,
                hoverBorderWidth: 4
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
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: '#10b981',
                    borderWidth: 2,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            const suffix = hasData ? ' pemain' : ' (simulasi)';
                            return label + ': ' + value + suffix + ' (' + percentage + '%)';
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