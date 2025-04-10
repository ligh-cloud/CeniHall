<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Movies Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: 0 auto; }
        input, button { margin: 5px 0; padding: 8px; }
        button { cursor: pointer; background: #4a90e2; color: white; border: none; border-radius: 4px; }
        button:hover { background: #357ae8; }
        .movie { border: 1px solid #ccc; padding: 15px; margin-top: 15px; border-radius: 4px; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; box-sizing: border-box; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background-color: #dff0d8; color: #3c763d; }
        .alert-error { background-color: #f2dede; color: #a94442; }
        .search-container { display: flex; margin-bottom: 20px; }
        .search-container input { flex-grow: 1; margin-right: 10px; }
    </style>
</head>
<body>

<h1>Movies Dashboard</h1>
<div id="alert-container"></div>

<!-- Search -->
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Search movies..." />
    <button onclick="searchMovies()">Search</button>
</div>

<!-- Create -->
<h2>Add New Movie</h2>
<div class="form-group">
    <label for="title">Title <span style="color: red;">*</span></label>
    <input type="text" id="title" placeholder="Enter movie title" required />
</div>
<div class="form-group">
    <label for="description">Description <span style="color: red;">*</span></label>
    <input type="text" id="description" placeholder="Enter movie description" required />
</div>
<div class="form-group">
    <label for="genre">Genre <span style="color: red;">*</span></label>
    <input type="text" id="genre" placeholder="Enter movie genre" required />
</div>
<div class="form-group">
    <label for="duration">Duration (HH:MM:SS) <span style="color: red;">*</span></label>
    <input type="text" id="duration" placeholder="e.g., 02:00:00" required pattern="^(?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)$" title="Format should be HH:MM:SS" />
</div>
<div class="form-group">
    <label for="movie_type">Movie Type <span style="color: red;">*</span></label>
    <input type="text" id="movie_type" placeholder="e.g., Action, Drama" required />
</div>
<div class="form-group">
    <label for="director">Director <span style="color: red;">*</span></label>
    <input type="text" id="director" placeholder="Enter director name" required />
</div>
<div class="form-group">
    <label for="image">Image URL (optional)</label>
    <input type="url" id="image" placeholder="Enter image URL" />
</div>
<div class="form-group">
    <label for="trailer">Trailer URL (optional)</label>
    <input type="url" id="trailer" placeholder="Enter trailer URL" />
</div>
<div class="form-group">
    <label for="min_age">Minimum Age <span style="color: red;">*</span></label>
    <input type="number" id="min_age" min="12" max="100" placeholder="e.g., 13" required />
</div>
<button onclick="createMovie()">Add Movie</button>

<!-- Movies List -->
<h2>All Movies</h2>
<button onclick="fetchAllMovies()">Refresh List</button>
<div id="moviesList"></div>

<script>
    // Get the base URL dynamically
    const baseUrl = window.location.protocol + '//' + window.location.host;

    // Show alert message
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertDiv);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Get CSRF token from meta tag
    function getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    // Create movie
    function createMovie() {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const genre = document.getElementById('genre').value.trim();
        const duration = document.getElementById('duration').value.trim();
        const movie_type = document.getElementById('movie_type').value.trim();
        const director = document.getElementById('director').value.trim();
        const min_age = document.getElementById('min_age').value.trim();

        // Validate required fields
        if (!title || !description || !genre || !duration || !movie_type || !director || !min_age) {
            showAlert('Please fill in all required fields', 'error');
            return;
        }

        // Validate duration format (HH:MM:SS)
        const durationPattern = /^(?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)$/;
        if (!durationPattern.test(duration)) {
            showAlert('Duration must be in HH:MM:SS format', 'error');
            return;
        }

        fetch(`${baseUrl}/api/movie`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify({ title, description, genre, duration, movie_type, director, min_age })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('Movie added successfully!');
                    // Clear form
                    document.getElementById('title').value = '';
                    document.getElementById('description').value = '';
                    document.getElementById('genre').value = '';
                    document.getElementById('duration').value = '';
                    document.getElementById('movie_type').value = '';
                    document.getElementById('director').value = '';
                    document.getElementById('min_age').value = '';
                    // Refresh the movie list
                    fetchAllMovies();
                } else {
                    showAlert(data.message || 'Unknown error occurred', 'error');
                }
            })
            .catch(err => {
                console.error('Error creating movie:', err);
                showAlert(err.message || 'Failed to add movie', 'error');
            });
    }

    // Fetch all movies
    function fetchAllMovies() {
        fetch(`${baseUrl}/api/movie/all`)
            .then(res => res.json())
            .then(data => {
                displayMovies(data);
            })
            .catch(err => {
                console.error('Error loading movies:', err);
                showAlert('Failed to load movies: ' + err.message, 'error');
            });
    }

    // Display movies
    function displayMovies(data) {
        const movies = Array.isArray(data) ? data : (data.data || []);
        const list = document.getElementById('moviesList');
        list.innerHTML = '';

        if (movies.length === 0) {
            list.innerHTML = '<p>No movies found.</p>';
            return;
        }

        movies.forEach(movie => {
            const div = document.createElement('div');
            div.className = 'movie';
            div.innerHTML = `
                <strong>${movie.title || 'No Title'}</strong><br>
                ${movie.description || 'No Description'}<br>
                Genre: ${movie.genre || 'No Genre'}<br>
                <button onclick="deleteMovie(${movie.id})">Delete</button>
            `;
            list.appendChild(div);
        });
    }

    // Delete movie
    function deleteMovie(id) {
        if (!confirm('Are you sure you want to delete this movie?')) return;

        fetch(`${baseUrl}/api/movie/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('Movie deleted successfully');
                    fetchAllMovies();
                } else {
                    showAlert(data.message || 'Delete failed', 'error');
                }
            })
            .catch(err => {
                console.error('Error deleting movie:', err);
                showAlert('Delete failed: ' + err.message, 'error');
            });
    }

    // Search movies
    function searchMovies() {
        const query = document.getElementById('searchInput').value.trim();
        if (!query) {
            showAlert('Please enter a search term', 'error');
            return;
        }

        fetch(`${baseUrl}/api/movie/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                displayMovies(data);
            })
            .catch(err => {
                console.error('Search error:', err);
                showAlert('Search failed: ' + err.message, 'error');
            });
    }
</script>

</body>
</html>
