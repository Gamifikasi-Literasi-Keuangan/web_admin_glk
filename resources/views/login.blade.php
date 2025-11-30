<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Gamifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Admin Login</h2>

        <form id="loginForm" onsubmit="handleLogin(event)">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="admin_lke" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="******************" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Masuk
                </button>
            </div>
            <p id="errorMsg" class="text-red-500 text-xs italic mt-4 hidden"></p>
        </form>
    </div>

    <script>
    const API_URL = "{{ url('/api/admin/auth/login') }}"; // URL API Laravel

    async function handleLogin(e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const errorMsg = document.getElementById('errorMsg');

        // Reset Error
        errorMsg.classList.add('hidden');
        errorMsg.innerText = '';

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
                // 2. Jika Sukses, Simpan Token di LocalStorage
                localStorage.setItem('admin_token', data.token);
                localStorage.setItem('admin_name', data.user.username);

                // 3. Redirect ke Dashboard
                window.location.href = '/dashboard';
            } else {
                throw new Error(data.error || 'Login gagal.');
            }

        } catch (error) {
            errorMsg.innerText = error.message;
            errorMsg.classList.remove('hidden');
        }
    }
    </script>
</body>

</html>