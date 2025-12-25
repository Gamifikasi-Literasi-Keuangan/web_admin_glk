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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Sidebar hover effects */
        .sidebar-item {
            transition: all 0.2s ease-in-out;
        }

        .sidebar-item:hover {
            background-color: rgba(139, 69, 19, 0.1);
            transform: translateX(4px);
        }

    .custom-scrollbar {
        overflow-x: auto;
    }

    /* Layout fixes */
    .main-layout {
        min-height: 100vh;
        display: flex;
        flex-direction: row;
    }

    .content-area {
        flex: 1;
        min-width: 0; /* Prevents flex overflow */
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 1023px) {
        .content-area {
            width: 100%;
        }
    }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-800 font-['Poppins'] antialiased">

    <div class="main-layout">
        
        @include('components.sidebar')

        <!-- Main Content Area -->
        <div class="content-area">
            <!-- Header -->
            <div class="w-full h-16 md:h-20 bg-green-600 lg:rounded-bl-2xl lg:rounded-br-2xl relative flex-shrink-0">
                <!-- Mobile Menu Button -->
                <button onclick="toggleSidebar()" class="lg:hidden absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-xl z-50">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="absolute left-12 lg:left-12 top-1/2 transform -translate-y-1/2">
                    <div class="text-white text-lg md:text-2xl font-bold font-['Poppins']">@yield('header', 'Dashboard')</div>
                </div>
            </div>

            <!-- Content -->
            <main class="flex-1 p-3 md:p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
    const BASE_API = "{{ url('/api/admin') }}";
    const token = localStorage.getItem('admin_token');

    if (!token) {
        window.location.href = '/login';
    }

    // Flag to prevent multiple redirects
    let isRedirecting = false;

    // Global fetch wrapper to handle 401 responses
    const originalFetch = window.fetch;
    window.fetch = function (...args) {
        return originalFetch.apply(this, args).then(response => {
            if (response.status === 401 && !isRedirecting) {
                // Token expired or invalid - only redirect if not already on login page
                if (!window.location.pathname.includes('/login')) {
                    isRedirecting = true;
                    console.log('401 Unauthorized - Token expired, redirecting to login');
                    localStorage.removeItem('admin_token');
                    localStorage.removeItem('admin_name');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 500);
                }
            }
            return response;
        });
    };

                    const adminName = localStorage.getItem('admin_name') || 'Admin GLK';
                    const adminDisplayElement = document.getElementById('adminNameDisplay');
                    if (adminDisplayElement) {
                        adminDisplayElement.innerText = adminName;
                    }

                    // Verify token periodically  
                    let isVerifying = false;
                    async function verifyToken() {
                        if (isVerifying) return; // Prevent concurrent verification

                        isVerifying = true;
                        try {
                            const response = await originalFetch('{{ url("/api/admin/analytics/overview") }}', {
                                method: 'GET',
                                headers: {
                                    'Authorization': `Bearer ${token}`,
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.status === 401 && !window.location.pathname.includes('/login')) {
                                console.log('Token verification failed - redirecting to login');
                                localStorage.removeItem('admin_token');
                                localStorage.removeItem('admin_name');
                                window.location.href = '/login';
                            }
                        } catch (error) {
                            console.error('Token verification error:', error);
                        } finally {
                            isVerifying = false;
                        }
                    }

                    // Verify token every 10 minutes (reduced frequency)
                    setInterval(verifyToken, 10 * 60 * 1000);

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

    // Mobile sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }
    
    // Close sidebar when clicking overlay
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
    </script>

    @stack('scripts')
</body>

</html>