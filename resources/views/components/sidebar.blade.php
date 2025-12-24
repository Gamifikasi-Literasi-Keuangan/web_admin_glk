<!-- Sidebar -->
<aside id="sidebar" class="w-72 h-screen absolute left-0 top-0 bg-slate-900 shadow-2xl">
    
    <!-- Header Section -->
    <div class="w-72 h-20 bg-gray-800 rounded-bl-2xl rounded-br-2xl shadow-2xl shadow-black/40 relative overflow-hidden">
        <!-- Subtle gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-violet-600/10 to-green-600/10 rounded-bl-2xl rounded-br-2xl"></div>
        
        <!-- Logo Icon -->
        <div class="flex items-center absolute left-[45px] top-[20px] z-10">
            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3 shadow-md shadow-black/25">
                <i class="fas fa-gamepad text-white text-lg"></i>
            </div>
            <div class="text-violet-100 text-xl font-bold font-['Poppins'] drop-shadow-sm">Gamifikasi</div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="pt-8 px-4">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="sidebar-item group flex items-center py-3 px-4 rounded-lg mb-2 transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('dashboard') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center">
                <i class="fas fa-chart-line text-violet-100 text-lg group-hover:text-green-400"></i>
            </div>
            <span class="ml-4 text-violet-100 text-base font-medium font-['Poppins'] group-hover:text-green-400">Dashboard</span>
        </a>

        <!-- Profiling Player -->
        <a href="{{ route('admin.players') }}" class="sidebar-item group flex items-center py-3 px-4 rounded-lg mb-2 transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/players*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center">
                <i class="fas fa-users text-violet-100 text-lg group-hover:text-green-400"></i>
            </div>
            <span class="ml-4 text-violet-100 text-base font-medium font-['Poppins'] group-hover:text-green-400">Profiling Player</span>
        </a>

        <!-- Session Game -->
        <a href="{{ route('admin.games') }}" class="sidebar-item group flex items-center py-3 px-4 rounded-lg mb-2 transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/games*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center">
                <i class="fas fa-chess-board text-violet-100 text-lg group-hover:text-green-400"></i>
            </div>
            <span class="ml-4 text-violet-100 text-base font-medium font-['Poppins'] group-hover:text-green-400">Session Game</span>
        </a>

        <!-- Recommendation -->
        <a href="{{ route('admin.content') }}" class="sidebar-item group flex items-center py-3 px-4 rounded-lg mb-2 transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/content*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center">
                <i class="fas fa-lightbulb text-violet-100 text-lg group-hover:text-green-400"></i>
            </div>
            <span class="ml-4 text-violet-100 text-base font-medium font-['Poppins'] group-hover:text-green-400">Recommendation</span>
        </a>

        <!-- Leaderboard -->
        <a href="{{ route('admin.analytics') }}" class="sidebar-item group flex items-center py-3 px-4 rounded-lg mb-2 transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/analytics*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
            <div class="w-8 h-8 flex items-center justify-center">
                <i class="fas fa-trophy text-violet-100 text-lg group-hover:text-green-400"></i>
            </div>
            <span class="ml-4 text-violet-100 text-base font-medium font-['Poppins'] group-hover:text-green-400">Leaderboard</span>
        </a>
    </nav>

    <!-- Admin Profile Section -->
    <div class="absolute bottom-24 left-0 w-72 px-4">
        <div class="bg-gray-800/50 rounded-xl p-4 border border-violet-100/20">
            <!-- Admin Profile -->
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-white text-lg"></i>
                </div>
                <div class="ml-3">
                    <div class="text-violet-100 text-base font-semibold font-['Poppins']" id="adminNameDisplay">Admin GLK</div>
                    <div class="text-violet-100/70 text-sm font-['Poppins']">Administrator</div>
                </div>
            </div>

            <!-- Settings -->
            <a href="{{ route('admin.settings') }}" class="sidebar-item group flex items-center py-2 px-3 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/settings*') ? 'bg-violet-100/20' : '' }}">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="fas fa-cog text-violet-100 group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 text-violet-100 text-base font-['Poppins'] group-hover:text-green-400">Settings</span>
            </a>
        </div>
    </div>

    <!-- Logout Button -->
    <div class="absolute bottom-4 left-4 right-4">
        <button onclick="handleLogout()" class="w-full bg-red-600 hover:bg-red-700 rounded-xl py-3 px-4 flex items-center justify-center transition-all duration-300 shadow-lg group">
            <i class="fas fa-sign-out-alt text-white text-lg mr-3 group-hover:rotate-12 transition-transform"></i>
            <span class="text-white text-base font-semibold font-['Poppins']">Logout</span>
        </button>
    </div>
</aside>