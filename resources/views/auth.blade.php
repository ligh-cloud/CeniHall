<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<h2>Register</h2>
<form id="register-form">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" required><br>
    <button type="submit">Register</button>
</form>

<h2>Login</h2>
<form id="login-form">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>

<h2>User Profile</h2>
<button id="get-user">Get Authenticated User</button>
<div id="user-info"></div>

<h2>Logout</h2>
<button id="logout">Logout</button>

<script>
    let token = null;

    const apiUrl = '/api'; // Adjust if needed

    // Helper function to handle form data
    function getFormData(form) {
        const formData = new FormData(form);
        return Object.fromEntries(formData.entries());
    }

    // REGISTER
    document.getElementById('register-form').addEventListener('submit', async function (e) {
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
            } else {
                alert(JSON.stringify(result.errors || result));
            }
        } catch (error) {
            alert('Registration failed');
            console.error(error);
        }
    });

    // LOGIN
    document.getElementById('login-form').addEventListener('submit', async function (e) {
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
