const headers = {
    Authorization: `Bearer ${token}`,
    Accept: "application/json",
};

document.addEventListener("DOMContentLoaded", () => {
    loadOverviewStats();

    // Listen untuk update dari players page
    document.addEventListener("playerListUpdated", () => {
        console.log("Player list updated, refreshing overview...");
        loadOverviewStats();
    });
});

async function loadOverviewStats() {
    // Efek loading dengan spinner hijau
    ['stat-players', 'stat-sessions', 'stat-decisions'].forEach(id => {
        document.getElementById(id).innerHTML = `
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-green-600 mr-2"></div>
                <span class="text-sm text-zinc-500">Loading...</span>
            </div>
        `;
    });

    try {
        // Panggil API Overview
        const res = await fetch(`${BASE_API}/analytics/overview`, { headers });

        if (res.status === 401) {
            window.location.href = "/login";
            return;
        }

        if (!res.ok) throw new Error("Gagal memuat data");

        const data = await res.json();

        // Update Angka di Dashboard dengan animasi
        animateValue("stat-players", 0, data.total_players || 0, 1000);
        animateValue("stat-sessions", 0, data.active_sessions || 0, 1000);
        animateValue("stat-decisions", 0, data.total_decisions || 0, 1000);
    } catch (e) {
        console.error(e);
        ['stat-players', 'stat-sessions', 'stat-decisions'].forEach(id => {
            document.getElementById(id).innerHTML = `
                <div class="flex items-center text-red-500">
                    <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                    <span class="text-sm">Error</span>
                </div>
            `;
        });
    }
}

// Fungsi Animasi Angka (Counter Up) dengan styling yang konsisten
function animateValue(id, start, end, duration) {
    const obj = document.getElementById(id);
    
    if (start === end) {
        obj.innerHTML = `<span class="text-green-600">${end.toLocaleString()}</span>`;
        return;
    }

    const range = end - start;
    let current = start;
    const increment = end > start ? 1 : -1;
    const stepTime = Math.abs(Math.floor(duration / range));

    const timer = setInterval(function () {
        current += increment;
        obj.innerHTML = `<span class="text-green-600">${current.toLocaleString()}</span>`;
        
        if (current == end) {
            clearInterval(timer);
            // Tambahkan efek pulse setelah animasi selesai
            obj.classList.add('animate-pulse');
            setTimeout(() => {
                obj.classList.remove('animate-pulse');
            }, 600);
        }
    }, stepTime);
}
