<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .form-container {
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            margin-right: 10px;
            font-weight: bold;
        }

        .form-container select, .form-container button {
            padding: 10px;
            margin-top: 10px;
            width: 100%;
        }

        .form-container button {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container button:hover {
            background-color: #555;
        }

        .movie-item {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .movie-item h3 {
            margin-top: 0;
            font-size: 18px;
            color: #333;
        }

        .movie-item p {
            font-size: 14px;
            color: #666;
        }

        .reservation-status {
            color: #28a745;
            font-weight: bold;
        }
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


<h2>Liste des Réservations (chargées via XHR)</h2>

<!-- Movies List -->
<h2>Liste des Films</h2>
<div id="movies-list">
    <!-- Movies will be displayed here -->
</div>

<h2>Réserver un Siège</h2>

<!-- Reservation Form -->
<div class="form-container">
    <form id="reservation-form">
        <label for="movie_id">Film:</label>
        <select id="movie_id" required>
            <option value="">Sélectionner un film</option>
        </select>

        <label for="siege_id">Siège:</label>
        <select id="siege_id" required>
            <option value="">Sélectionner un siège</option>
        </select>

        <button type="submit">Réserver</button>
    </form>
</div>

<h2>Liste des Réservations</h2>
<!-- Reservations Table -->
<table id="reservations-table">
    <thead>
    <tr>
        <th>Utilisateur</th>
        <th>Email</th>
        <th>Siège</th>
        <th>Film</th>
        <th>Salle</th>
        <th>Langue</th>
        <th>Type</th>
        <th>Date & Heure</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <!-- JS will fill this -->
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const moviesList = document.querySelector('#movies-list');
        const movieSelect = document.querySelector('#movie_id');
        const siegeSelect = document.querySelector('#siege_id');
        const reservationForm = document.querySelector('#reservation-form');
        const reservationsTable = document.querySelector('#reservations-table tbody');

        // Load Movies and Display Them
        function loadMovies() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '/api/movies', true); // API endpoint for movies
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const movies = JSON.parse(xhr.responseText);
                    moviesList.innerHTML = ''; // Clear previous movie list
                    movieSelect.innerHTML = `<option value="">Sélectionner un film</option>`; // Reset movie select

                    movies.forEach(movie => {
                        // Display movie details
                        const movieDiv = document.createElement('div');
                        movieDiv.classList.add('movie-item');
                        movieDiv.innerHTML = `
                            <h3>${movie.title}</h3>
                            <p>${movie.description}</p>
                        `;
                        moviesList.appendChild(movieDiv);

                        // Add movie to dropdown list
                        const option = document.createElement('option');
                        option.value = movie.id;
                        option.textContent = movie.title;
                        movieSelect.appendChild(option);
                    });
                } else {
                    console.error('Erreur lors du chargement des films');
                }
            };
            xhr.send();
        }

        // Load Seats based on selected movie
        movieSelect.addEventListener('change', function () {
            const movieId = movieSelect.value;
            if (movieId) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `/api/seats?movie_id=${movieId}`, true); // API endpoint for seats based on movie
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const seats = JSON.parse(xhr.responseText);
                        siegeSelect.innerHTML = `<option value="">Sélectionner un siège</option>`; // Reset siege select
                        seats.forEach(seat => {
                            const option = document.createElement('option');
                            option.value = seat.id;
                            option.textContent = `Siège ${seat.siege_number}`;
                            siegeSelect.appendChild(option);
                        });
                    } else {
                        console.error('Erreur lors du chargement des sièges');
                    }
                };
                xhr.send();
            }
        });

        // Handle reservation form submission
        reservationForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = {
                movie_id: movieSelect.value,
                siege_id: siegeSelect.value,
            };

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/api/reservations', true); // API endpoint for reservations
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = function () {
                if (xhr.status === 200 || xhr.status === 201) {
                    alert('Réservation effectuée avec succès!');
                    loadReservations(); // Reload reservations table
                    reservationForm.reset(); // Reset the reservation form
                } else {
                    console.error('Erreur lors de la réservation');
                }
            };
            xhr.send(JSON.stringify(formData));
        });

        // Load reservations into the table
        function loadReservations() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '/api/reservations', true); // API endpoint for reservations
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const reservations = JSON.parse(xhr.responseText);
                    reservationsTable.innerHTML = ''; // Clear previous reservations
                    reservations.forEach(r => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${r.user?.name ?? 'N/A'}</td>
                            <td>${r.user?.email ?? 'N/A'}</td>
                            <td>${r.siege?.siege_number ?? 'N/A'}</td>
                            <td>${r.seance?.movie?.title ?? 'N/A'}</td>
                            <td>${r.seance?.salle?.name ?? 'N/A'}</td>
                            <td>${r.seance?.language ?? 'N/A'}</td>
                            <td>${r.seance?.type ?? 'N/A'}</td>
                            <td>${formatDate(r.seance?.start_time)}</td>
                            <td class="reservation-status">${r.status ? 'Payée' : 'Non payée'}</td>
                        `;
                        reservationsTable.appendChild(row);
                    });
                } else {
                    console.error('Erreur lors du chargement des réservations');
                }
            };
            xhr.send();
        }

        // Format date function
        function formatDate(datetime) {
            if (!datetime) return 'N/A';
            const date = new Date(datetime);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        // Initial load of movies and reservations
        loadMovies();
        loadReservations();
    });
</script>

</body>
</html>
