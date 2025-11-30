<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Gamifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Agar tabel bisa discroll di HP */
    .custom-scrollbar {
        overflow-x: auto;
    }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <div id="sidebar-overlay" onclick="toggleSidebar()"
            class="fixed inset-0 z-20 bg-black opacity-50 hidden md:hidden transition-opacity lg:hidden"></div>

        @include('components.sidebar')

        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">

            <header class="h-16 bg-white shadow flex items-center justify-between px-4 md:px-6 z-10 shrink-0">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-700 truncate">@yield('header', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <span class="block text-sm font-bold text-gray-700" id="adminNameDisplay">Admin</span>
                        <span class="block text-xs text-gray-500">Administrator</span>
                    </div>
                    <div
                        class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                        A</div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
    const BASE_API = "{{ url('/api/admin') }}";
    const token = localStorage.getItem('admin_token');

    if (!token) window.location.href = '/login';

    document.getElementById('adminNameDisplay').innerText = localStorage.getItem('admin_name') || 'Admin';

    function handleLogout() {
        if (confirm('Yakin ingin keluar?')) {
            localStorage.clear();
            window.location.href = '/login';
        }
    }

    // --- LOGIC RESPONSIVE SIDEBAR ---
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        // Toggle kelas translate untuk animasi slide
        if (sidebar.classList.contains('-translate-x-full')) {
            // Buka Menu
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            // Tutup Menu
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
    </script>

    @stack('scripts')
</body>

</html>