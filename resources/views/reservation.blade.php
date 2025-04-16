<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #222;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<h2>Liste des Réservations (chargées via XHR)</h2>

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
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/api/reservations', true); // Make sure this route returns JSON
        xhr.onload = function () {
            if (xhr.status === 200) {
                const reservations = JSON.parse(xhr.responseText);
                const tbody = document.querySelector('#reservations-table tbody');
                tbody.innerHTML = '';

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
                        <td>${r.status ? 'Payée' : 'Non payée'}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                console.error('Erreur lors du chargement des réservations');
            }
        };
        xhr.send();

        function formatDate(datetime) {
            if (!datetime) return 'N/A';
            const date = new Date(datetime);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    });
</script>

</body>
</html>
