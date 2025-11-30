const headers = {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json'
};

document.addEventListener('DOMContentLoaded', () => {
    loadOverviewStats();
});

async function loadOverviewStats() {
    // Efek loading sederhana
    ['stat-players', 'stat-sessions', 'stat-decisions'].forEach(id => {
        document.getElementById(id).innerHTML = '<span class="text-sm text-gray-400">...</span>';
    });

    try {
        // Panggil API Overview
        const res = await fetch(`${BASE_API}/analytics/overview`, { headers });
        
        if (res.status === 401) {
            window.location.href = '/login';
            return;
        }

        if (!res.ok) throw new Error("Gagal memuat data");

        const data = await res.json();

        // Update Angka di Dashboard
        animateValue("stat-players", 0, data.total_players || 0, 1000);
        animateValue("stat-sessions", 0, data.active_sessions || 0, 1000);
        animateValue("stat-decisions", 0, data.total_decisions || 0, 1000);

    } catch (e) {
        console.error(e);
        ['stat-players', 'stat-sessions', 'stat-decisions'].forEach(id => {
            document.getElementById(id).innerText = 'Err';
            document.getElementById(id).classList.add('text-red-500');
        });
    }
}

// Fungsi Animasi Angka (Counter Up)
function animateValue(id, start, end, duration) {
    if (start === end) {
        document.getElementById(id).innerText = end;
        return;
    }
    
    const range = end - start;
    let current = start;
    const increment = end > start ? 1 : -1;
    const stepTime = Math.abs(Math.floor(duration / range));
    const obj = document.getElementById(id);
    
    const timer = setInterval(function() {
        current += increment;
        obj.innerText = current;
        if (current == end) {
            clearInterval(timer);
        }
    }, stepTime);
}