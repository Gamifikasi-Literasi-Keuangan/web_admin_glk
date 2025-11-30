<aside id="sidebar" class="bg-gray-900 text-white flex flex-col shadow-2xl 
    fixed inset-y-0 left-0 z-30 w-64 
    transform -translate-x-full transition-transform duration-300 ease-in-out 
    md:translate-x-0 md:static md:inset-0">

    <div class="h-16 flex items-center justify-center border-b border-gray-800 bg-gray-900 shadow-md shrink-0">
        <h1 class="text-xl font-bold tracking-wider flex items-center gap-2">
            <i class="fa-solid fa-gamepad text-blue-500"></i>
            <span>GAMIFIKASI</span>
        </h1>
        <button onclick="toggleSidebar()" class="md:hidden absolute right-4 text-gray-400 hover:text-white">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto custom-scrollbar">
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 {{ request()->is('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all">
            <i class="fa-solid fa-chart-line w-6"></i>
            <span class="font-medium">Overview</span>
        </a>

        <a href="{{ route('admin.players') }}"
            class="flex items-center px-4 py-3 {{ request()->is('admin/players*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all">
            <i class="fa-solid fa-users w-6"></i>
            <span class="font-medium">Manajemen Pemain</span>
        </a>

        <a href="{{ route('admin.content') }}"
            class="flex items-center px-4 py-3 {{ request()->is('admin/content*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all">
            <i class="fa-solid fa-book-open w-6"></i>
            <span class="font-medium">Konten & Skenario</span>
        </a>

        <a href="{{ route('admin.games') }}"
            class="flex items-center px-4 py-3 {{ request()->is('admin/games*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all">
            <i class="fa-solid fa-chess-board w-6"></i>
            <span class="font-medium">Sesi Permainan</span>
        </a>

        <a href="{{ route('admin.analytics') }}"
            class="flex items-center px-4 py-3 {{ request()->is('admin/analytics*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all">
            <i class="fa-solid fa-chart-pie w-6"></i>
            <span class="font-medium">Analitik & Laporan</span>
        </a>

        <a href="{{ route('admin.settings') }}"
            class="flex items-center px-4 py-3 {{ request()->is('admin/settings*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg transition-all">
            <i class="fa-solid fa-cogs w-6"></i>
            <span class="font-medium">Pengaturan</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-800 bg-gray-900 shrink-0">
        <button onclick="handleLogout()"
            class="flex items-center justify-center w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors shadow-md font-bold">
            <i class="fa-solid fa-sign-out-alt mr-2"></i>
            <span>Keluar</span>
        </button>
    </div>
</aside>