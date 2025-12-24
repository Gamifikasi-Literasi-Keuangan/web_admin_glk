<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Gamifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #1e293b;
    }

    ::-webkit-scrollbar-thumb {
        background: #16a34a;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #15803d;
    }

    .loader {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #16a34a;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Sidebar hover effects */
    .sidebar-item {
        transition: all 0.2s ease-in-out;
    }

    .sidebar-item:hover {
        background-color: rgba(139, 69, 19, 0.1);
        transform: translateX(4px);
    }

    .sidebar-item.active {
        background-color: rgba(139, 69, 19, 0.2);
        border-left: 4px solid #a855f7;
    }

    .custom-scrollbar {
        overflow-x: auto;
    }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-800 font-['Poppins'] antialiased">

    <div class="w-full h-screen relative overflow-hidden">
        
        @include('components.sidebar')

        <!-- Main Content Area -->
        <div class="absolute left-72 top-0 right-0 bottom-0">
            <!-- Header -->
            <div class="w-full h-20 bg-green-600 rounded-bl-2xl rounded-br-2xl relative">
                <div class="absolute left-12 top-[19px]">
                    <div class="text-black text-2xl font-normal font-['Poppins']">@yield('header', 'Dashboard')</div>
                </div>
            </div>

            <!-- Content -->
            <main class="p-6 h-[calc(100vh-80px)] overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
    const BASE_API = "{{ url('/api/admin') }}";
    const token = localStorage.getItem('admin_token');

    if (!token) window.location.href = '/login';

    // Update admin names
    const adminName = localStorage.getItem('admin_name') || 'Admin GLK';
    const adminDisplayElement = document.getElementById('adminNameDisplay');
    if (adminDisplayElement) {
        adminDisplayElement.innerText = adminName;
    }

    // Real-time clock
    function updateClock() {
        const clockElement = document.getElementById('current-time');
        if (clockElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'short',
                day: 'numeric',
                month: 'short'
            });
            clockElement.innerHTML = `
                <div>
                    <div class="font-semibold">${timeString}</div>
                    <div class="text-xs opacity-80">${dateString}</div>
                </div>
            `;
        }
    }

    // Update clock every second
    updateClock();
    setInterval(updateClock, 1000);

    function handleLogout() {
        if (confirm('Yakin ingin keluar?')) {
            localStorage.clear();
            window.location.href = '/login';
        }
    }

    // Mobile sidebar toggle (if needed)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }
    </script>

    @stack('scripts')
</body>

</html>