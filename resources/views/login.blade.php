<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Gamifikasi Literasi Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e293b, #334155);
        }
    </style>
</head>

<body class="bg-slate-800 flex items-center justify-center h-screen">

    <div class="bg-slate-900 p-8 rounded-lg shadow-xl border border-slate-700 w-96">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-coins text-white text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-white font-['Poppins']">Admin Login</h2>
            <p class="text-slate-400 text-sm mt-2 font-['Poppins']">Gamifikasi Literasi Keuangan</p>
        </div>

        <form id="loginForm" onsubmit="handleLogin(event)">
            <div class="mb-4">
                <label class="block text-slate-300 text-sm font-medium mb-2 font-['Poppins']">
                    <i class="fas fa-user mr-2 text-green-500"></i>Username
                </label>
                <input type="text" id="username"
                    class="w-full py-3 px-4 bg-slate-800 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 font-['Poppins']"
                    placeholder="Masukkan username admin" required>
            </div>

            <div class="mb-6">
                <label class="block text-slate-300 text-sm font-medium mb-2 font-['Poppins']">
                    <i class="fas fa-lock mr-2 text-green-500"></i>Password
                </label>
                <input type="password" id="password"
                    class="w-full py-3 px-4 bg-slate-800 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 font-['Poppins']"
                    placeholder="Masukkan password" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/50 w-full transition-colors font-['Poppins']">
                    <span id="btnText">Masuk ke Dashboard</span>
                </button>
            </div>
            
            <p id="errorMsg" class="text-red-400 text-sm mt-4 hidden bg-red-900/20 border border-red-500/30 p-3 rounded-lg font-['Poppins']"></p>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-slate-500 text-xs font-['Poppins']">
                &copy; 2024 Gamifikasi Literasi Keuangan
            </p>
        </div>
    </div>

    <script>
    const API_URL = "{{ url('/api/admin/auth/login') }}"; // URL API Laravel

    async function handleLogin(e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const errorMsg = document.getElementById('errorMsg');
        const btnText = document.getElementById('btnText');
        const submitBtn = e.target.querySelector('button[type="submit"]');

        // Reset Error
        errorMsg.classList.add('hidden');
        errorMsg.innerText = '';

        // Loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75');
        btnText.innerText = 'Memproses...';

        try {
            // 1. Panggil API Login
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    username,
                    password
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Success state
                btnText.innerText = 'Berhasil!';
                submitBtn.classList.remove('opacity-75');
                submitBtn.classList.add('bg-green-500');

                // 2. Jika Sukses, Simpan Token di LocalStorage
                localStorage.setItem('admin_token', data.token);
                localStorage.setItem('admin_name', data.user.username);

                // 3. Redirect ke Dashboard
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1000);
            } else {
                throw new Error(data.error || data.message || 'Login gagal.');
            }

        } catch (error) {
            // Error state
            errorMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>' + error.message;
            errorMsg.classList.remove('hidden');
            
            // Reset button
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'bg-green-500');
            btnText.innerText = 'Masuk ke Dashboard';
        }
    }

    // Check if already logged in
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('admin_token');
        if (token) {
            window.location.href = '/dashboard';
        }
    });
    </script>
</body>

</html>