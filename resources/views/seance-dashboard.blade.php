<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Seances Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: 0 auto; }
        input, select, button { margin: 5px 0; padding: 8px; width: 100%; box-sizing: border-box; }
        button { cursor: pointer; background: #28a745; color: white; border: none; border-radius: 4px; }
        button:hover { background: #218838; }
        .seance { border: 1px solid #ccc; padding: 15px; margin-top: 15px; border-radius: 4px; }
        .form-group { margin-bottom: 10px; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
    <a class="navbar-brand" href="/">MyApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="/login">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/post">Posts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/salle">Salle</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/test">Test</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/seances">Séances</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/reservations">Réservations</a>
            </li>
        </ul>
    </div>
</nav>

<h1>Seances Dashboard</h1>
<div id="alert-container"></div>

<!-- Add Seance -->
<h2>Add New Seance</h2>
<div class="form-group">
    <label for="movie_id">Movie</label>
    <select id="movie_id"></select>
</div>
<div class="form-group">
    <label for="salle_id">Salle</label>
    <select id="salle_id"></select>
</div>
<div class="form-group">
    <label for="start_time">Start Time</label>
    <input type="datetime-local" id="start_time" />
</div>
<div class="form-group">
    <label for="end_time">End Time</label>
    <input type="datetime-local" id="end_time" />
</div>
<button onclick="createSeance()">Add Seance</button>

<!-- Seance List -->
<h2>All Seances</h2>
<button onclick="fetchAllSeances()">Refresh</button>
<div id="seanceList"></div>

<script>
    const baseUrl = window.location.origin;
    const apiSeances = baseUrl + '/api/seances';
    const apiMovies = baseUrl + '/api/movies';
    const apiSalles = baseUrl + '/api/salles';

    function getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        alertContainer.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => alertContainer.innerHTML = '', 4000);
    }

    function loadDropdowns() {
        fetch(apiMovies).then(res => res.json()).then(data => {
            const movies = Array.isArray(data) ? data : data.data;
            const select = document.getElementById('movie_id');
            movies.forEach(movie => {
                const option = document.createElement('option');
                option.value = movie.id;
                option.text = movie.title;
                select.appendChild(option);
            });
        });

        fetch(apiSalles).then(res => res.json()).then(data => {
            const salles = Array.isArray(data) ? data : data.data;
            const select = document.getElementById('salle_id');
            salles.forEach(salle => {
                const option = document.createElement('option');
                option.value = salle.id;
                option.text = salle.name;
                select.appendChild(option);
            });
        });
    }

    function fetchAllSeances() {
        fetch(apiSeances)
            .then(res => res.json())
            .then(data => {
                const seances = Array.isArray(data) ? data : data.data;
                const list = document.getElementById('seanceList');
                list.innerHTML = '';

                if (seances.length === 0) {
                    list.innerHTML = '<p>No seances found.</p>';
                    return;
                }

                seances.forEach(seance => {
                    const div = document.createElement('div');
                    div.className = 'seance';
                    div.innerHTML = `
                        <strong>Movie ID:</strong> ${seance.movie_id}<br>
                        <strong>Salle ID:</strong> ${seance.salle_id}<br>
                        <strong>Start:</strong> ${new Date(seance.start_time).toLocaleString()}<br>
                        <strong>End:</strong> ${new Date(seance.end_time).toLocaleString()}<br>
                        <button onclick="deleteSeance(${seance.id})">Delete</button>
                    `;
                    list.appendChild(div);
                });
            })
            .catch(err => {
                console.error(err);
                showAlert('Failed to load seances.', 'error');
            });
    }

    function createSeance() {
        const movie_id = document.getElementById('movie_id').value;
        const salle_id = document.getElementById('salle_id').value;
        const start_time = document.getElementById('start_time').value;
        const end_time = document.getElementById('end_time').value;

        if (!movie_id || !salle_id || !start_time || !end_time) {
            return showAlert('All fields are required.', 'error');
        }

        fetch(apiSeances, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify({ movie_id, salle_id, start_time, end_time })
        })
            .then(res => {
                if (!res.ok) return res.json().then(e => { throw new Error(e.message) });
                return res.json();
            })
            .then(() => {
                showAlert('Seance created successfully!');
                fetchAllSeances();
            })
            .catch(err => showAlert(err.message || 'Failed to create seance', 'error'));
    }

    function deleteSeance(id) {
        if (!confirm('Are you sure you want to delete this seance?')) return;

        fetch(`${apiSeances}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
            .then(res => res.json())
            .then(() => {
                showAlert('Seance deleted.');
                fetchAllSeances();
            })
            .catch(err => showAlert('Error deleting seance', 'error'));
    }

    window.onload = () => {
        loadDropdowns();
        fetchAllSeances();
    };
</script>

</body>
</html>
