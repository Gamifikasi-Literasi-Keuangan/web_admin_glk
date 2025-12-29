<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed lg:relative w-72 h-full lg:h-screen top-0 left-0 bg-slate-900 shadow-2xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
    
    <!-- Header Section -->
    <div class="w-full h-16 md:h-20 bg-gray-800 lg:rounded-bl-2xl lg:rounded-br-2xl shadow-2xl shadow-black/40 relative overflow-hidden flex-shrink-0">
        <!-- Close button for mobile -->
        <button onclick="closeSidebar()" class="lg:hidden absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-xl z-10">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Subtle gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-violet-600/10 to-green-600/10 lg:rounded-bl-2xl lg:rounded-br-2xl"></div>
        
        <!-- Logo Icon -->
        <div class="flex items-center absolute left-4 md:left-[45px] top-1/2 transform -translate-y-1/2 z-10">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3 shadow-md shadow-black/25">
                <i class="fas fa-gamepad text-white text-base md:text-lg"></i>
            </div>
            <div class="text-violet-100 text-lg md:text-xl font-bold font-['Poppins'] drop-shadow-sm">Gamifikasi</div>
        </div>
    </div>

    <!-- Navigation Menu (Scrollable) -->
    <div class="flex-1 overflow-y-auto custom-scrollbar nav-container py-6 px-3 md:px-4">
        <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="sidebar-item group flex items-center py-3 px-3 md:px-4 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('dashboard') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-chart-line text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Ringkasan Sistem</span>
            </a>

            <!-- Manajemen Player -->
            <a href="{{ route('admin.players') }}" class="sidebar-item group flex items-center py-3 px-3 md:px-4 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/players*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-users text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Manajemen Player</span>
            </a>

            <!-- Session Game -->
            <a href="{{ route('admin.games') }}" class="sidebar-item group flex items-center py-3 px-3 md:px-4 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/games*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-chess-board text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Session Game</span>
            </a>

            <!-- Pustaka Konten Game -->
            <a href="{{ route('admin.content') }}" class="sidebar-item group flex items-center py-3 px-3 md:px-4 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/content*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-archive text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Pustaka Konten</span>
            </a>

            <!-- Data Intelligence -->
            <a href="{{ route('admin.analytics') }}" class="sidebar-item group flex items-center py-3 px-3 md:px-4 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/analytics*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-chart-pie text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Data Intelligence</span>
            </a>

            <!-- Neural Network (ANN) -->
            <a href="{{ route('admin.ann') }}" class="sidebar-item group flex items-center py-3 px-3 md:px-4 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/ann*') ? 'bg-violet-100/20 border-l-4 border-green-500' : '' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-brain text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Neural Network</span>
            </a>

            <!-- Training Data -->
            <a href="{{ route('admin.training-data') }}" 
               class="sidebar-item group flex items-center py-2 md:py-3 px-3 md:px-4 rounded-xl transition-all duration-300 {{ request()->is('admin/training-data*') ? 'bg-violet-100/20 shadow-lg' : 'hover:bg-violet-100/10' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-database text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Training Data</span>
            </a>

            <!-- Profiling Questions -->
            <a href="{{ route('admin.profiling-questions') }}" 
               class="sidebar-item group flex items-center py-2 md:py-3 px-3 md:px-4 rounded-xl transition-all duration-300 {{ request()->is('admin/profiling-questions*') ? 'bg-violet-100/20 shadow-lg' : 'hover:bg-violet-100/10' }}">
                <div class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center">
                    <i class="fas fa-question-circle text-violet-100 text-base md:text-lg group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 md:ml-4 text-violet-100 text-sm md:text-base font-medium font-['Poppins'] group-hover:text-green-400">Profiling Questions</span>
            </a>
        </nav>
    </div>

    <!-- Admin Profile & Logout Section (Fixed at Bottom) -->
    <div class="flex-shrink-0 p-3 md:p-4 bg-slate-900 border-t border-violet-100/10 z-20">
        <div class="bg-gray-800/50 rounded-xl p-3 md:p-4 border border-violet-100/20 mb-3 md:mb-4">
            <!-- Admin Profile -->
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-white text-base md:text-lg"></i>
                </div>
                <div class="ml-3">
                    <div class="text-violet-100 text-sm md:text-base font-semibold font-['Poppins']" id="adminNameDisplay">Admin GLK</div>
                    <div class="text-violet-100/70 text-xs md:text-sm font-['Poppins']">Administrator</div>
                </div>
            </div>

            <!-- Settings -->
            <a href="{{ route('admin.settings') }}" class="sidebar-item group flex items-center py-2 px-3 rounded-lg transition-all duration-300 hover:bg-violet-100/10 {{ request()->is('admin/settings*') ? 'bg-violet-100/20' : '' }}">
                <div class="w-5 h-5 md:w-6 md:h-6 flex items-center justify-center">
                    <i class="fas fa-cog text-violet-100 text-sm md:text-base group-hover:text-green-400"></i>
                </div>
                <span class="ml-3 text-violet-100 text-sm md:text-base font-['Poppins'] group-hover:text-green-400">Settings</span>
            </a>
        </div>

        <!-- Logout Button -->
        <button onclick="handleLogout()" class="w-full bg-red-600 hover:bg-red-700 rounded-xl py-2 md:py-3 px-3 md:px-4 flex items-center justify-center transition-all duration-300 shadow-lg group">
            <i class="fas fa-sign-out-alt text-white text-base md:text-lg mr-2 md:mr-3 group-hover:rotate-12 transition-transform"></i>
            <span class="text-white text-sm md:text-base font-semibold font-['Poppins']">Logout</span>
        </button>
    </div>
</aside>