<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Toggle Buttons -->
    <div class="flex border-b">
        <button id="login-tab" class="flex-1 py-4 px-6 text-center font-medium text-gray-700 border-b-2 border-blue-500 transition-colors duration-300">
            Login
        </button>
        <button id="register-tab" class="flex-1 py-4 px-6 text-center font-medium text-gray-500 hover:text-gray-700 transition-colors duration-300">
            Register
        </button>
    </div>

    <!-- Forms Container -->
    <div class="p-6">
        <!-- Login Form -->
        <form id="login-form" class="space-y-4">
            <div>
                <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="login-email" name="email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="login-password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Login
            </button>
        </form>

        <!-- Register Form (hidden by default) -->
        <form id="register-form" class="space-y-4 hidden">
            <div>
                <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" id="register-name" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="register-email" name="email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="register-password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="register-password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" id="register-password-confirm" name="password_confirmation" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Register
            </button>
        </form>
    </div>

    <!-- User Section -->
    <div id="user-section" class="p-6 border-t border-gray-200 hidden">
        <h3 class="text-lg font-medium text-gray-900 mb-4">User Profile</h3>
        <div id="user-info" class="bg-gray-50 p-4 rounded-md mb-4 text-sm"></div>
        <div class="flex space-x-3">
            <button id="get-user"
                    class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Get User
            </button>
            <button id="logout"
                    class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Logout
            </button>
        </div>
    </div>
</div>

<script>
    let token = null;
    const apiUrl = '/api'; // Adjust if needed

    // DOM Elements
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const userSection = document.getElementById('user-section');

    // Toggle between forms
    loginTab.addEventListener('click', () => {
        loginTab.classList.add('border-blue-500', 'text-gray-700');
        loginTab.classList.remove('text-gray-500');
        registerTab.classList.add('text-gray-500');
        registerTab.classList.remove('border-blue-500', 'text-gray-700');
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    });

    registerTab.addEventListener('click', () => {
        registerTab.classList.add('border-blue-500', 'text-gray-700');
        registerTab.classList.remove('text-gray-500');
        loginTab.classList.add('text-gray-500');
        loginTab.classList.remove('border-blue-500', 'text-gray-700');
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
    });

    // Helper function to handle form data
    function getFormData(form) {
        const formData = new FormData(form);
        return Object.fromEntries(formData.entries());
    }

    // REGISTER
    registerForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const data = getFormData(this);

        try {
            const response = await fetch(`${apiUrl}/register`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                token = result.token;
                userSection.classList.remove('hidden');
            } else {
                alert(JSON.stringify(result.errors || result));
            }
        } catch (error) {
            alert('Registration failed');
            console.error(error);
        }
    });

    // LOGIN
    loginForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const data = getFormData(this);

        try {
            const response = await fetch(`${apiUrl}/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                token = result.token;
                userSection.classList.remove('hidden');
            } else {
                alert(result.error || 'Login failed');
            }
        } catch (error) {
            alert('Login error');
            console.error(error);
        }
    });

    // GET USER
    document.getElementById('get-user').addEventListener('click', async () => {
        try {
            const response = await fetch(`${apiUrl}/user`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                document.getElementById('user-info').textContent = JSON.stringify(result.user, null, 2);
            } else {
                alert(result.error || 'Failed to get user');
            }
        } catch (error) {
            alert('Error fetching user');
            console.error(error);
        }
    });

    // LOGOUT
    document.getElementById('logout').addEventListener('click', async () => {
        try {
            const response = await fetch(`${apiUrl}/logout`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                token = null;
                userSection.classList.add('hidden');
            } else {
                alert(result.error || 'Logout failed');
            }
        } catch (error) {
            alert('Logout error');
            console.error(error);
        }
    });
</script>
</body>
</html>
