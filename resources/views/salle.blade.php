<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Salles Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        input, button { margin: 5px 0; padding: 8px; width: 100%; box-sizing: border-box; }
        button { cursor: pointer; background: #4a90e2; color: white; border: none; border-radius: 4px; }
        button:hover { background: #357ae8; }
        .salle { border: 1px solid #ccc; padding: 15px; margin-top: 15px; border-radius: 4px; }
        .form-group { margin-bottom: 10px; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background-color: #dff0d8; color: #3c763d; }
        .alert-error { background-color: #f2dede; color: #a94442; }
    </style>
</head>
<body>

<h1>Salles Dashboard</h1>
<div id="alert-container"></div>

<!-- Create Salle -->
<h2>Add New Salle</h2>
<div class="form-group">
    <label for="salleName">Salle Name</label>
    <input type="text" id="salleName" placeholder="Enter salle name" />
</div>
<div class="form-group">
    <label for="capacity">Capacity</label>
    <input type="number" id="capacity" placeholder="Enter capacity" min="1" />
</div>
<button onclick="createSalle()">Add Salle</button>

<!-- Salle List -->
<h2>All Salles</h2>
<button onclick="fetchAllSalles()">Refresh List</button>
<div id="salleList"></div>

<script>
    const baseUrl = window.location.protocol + '//' + window.location.host;
    const salleApi = baseUrl + '/api/salles';

    function getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        alertContainer.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        setTimeout(() => alertContainer.innerHTML = '', 5000);
    }

    function displaySalles(data) {
        const salles = Array.isArray(data) ? data : (data.data || []);
        const list = document.getElementById('salleList');
        list.innerHTML = '';

        if (salles.length === 0) {
            list.innerHTML = '<p>No salles found.</p>';
            return;
        }

        salles.forEach(salle => {
            const div = document.createElement('div');
            div.className = 'salle';
            div.innerHTML = `
                <strong>${salle.name}</strong><br>
                Capacity: ${salle.capacity}<br>
                <button onclick="deleteSalle(${salle.id})">Delete</button>
            `;
            list.appendChild(div);
        });
    }

    function fetchAllSalles() {
        fetch(`${salleApi}`)
            .then(res => res.json())
            .then(data => displaySalles(data))
            .catch(err => {
                console.error('Failed to load salles:', err);
                showAlert('Failed to load salles: ' + err.message, 'error');
            });
    }

    function createSalle() {
        const name = document.getElementById('salleName').value.trim();
        const capacity = document.getElementById('capacity').value.trim();

        if (!name || !capacity) {
            showAlert('Please fill all fields', 'error');
            return;
        }

        fetch(salleApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify({ name, capacity })
        })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => { throw new Error(err.message || 'Validation error') });
                }
                return res.json();
            })
            .then(data => {
                showAlert('Salle added successfully!');
                document.getElementById('salleName').value = '';
                document.getElementById('capacity').value = '';
                fetchAllSalles();
            })
            .catch(err => {
                console.error('Error adding salle:', err);
                showAlert(err.message || 'Failed to add salle', 'error');
            });
    }

    function deleteSalle(id) {
        if (!confirm('Are you sure you want to delete this salle?')) return;

        fetch(`${salleApi}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
            .then(res => res.json())
            .then(data => {
                showAlert('Salle deleted successfully');
                fetchAllSalles();
            })
            .catch(err => {
                console.error('Error deleting salle:', err);
                showAlert('Delete failed: ' + err.message, 'error');
            });
    }

    window.onload = fetchAllSalles;
</script>

</body>
</html>
