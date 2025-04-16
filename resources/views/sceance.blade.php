<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seances Viewer</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 2rem;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        #ajax {
            max-width: 900px;
            margin: 2rem auto;
            display: grid;
            gap: 1.5rem;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            border-left: 5px solid #007bff;
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0 0 0.5rem;
            color: #007bff;
        }

        .card p {
            margin: 0.25rem 0;
            color: #333;
        }

        .tag {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-left: 0.5rem;
        }

        .vip {
            background-color: #e74c3c;
            color: white;
        }

        .normal {
            background-color: #2ecc71;
            color: white;
        }

        /* Form Styling */
        form {
            max-width: 600px;
            margin: 2rem auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="datetime-local"],
        button {
            width: 100%;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        input[type="text"]:focus,
        input[type="datetime-local"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        h2 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>

<h1>🎬 All Seances</h1>
<div id="ajax"></div>

<h2>➕ Add New Seance</h2>
<form id="createForm">
    <input type="text" placeholder="Movie ID" name="movie_id" required><br><br>
    <input type="text" placeholder="Salle ID" name="salle_id" required><br><br>
    <input type="text" placeholder="Type (vip or normal)" name="type" required><br><br>
    <input type="text" placeholder="Language" name="language" required><br><br>
    <input type="datetime-local" name="start_time" required><br><br>
    <input type="datetime-local" name="end_time"><br><br>
    <button type="submit">Create Seance</button>
</form>

<script>
    const xml = new XMLHttpRequest();
    xml.open("GET", "http://127.0.0.1:8000/api/seances", true);

    xml.onreadystatechange = function () {
        if (xml.readyState === 4 && xml.status === 200) {
            let data = JSON.parse(xml.responseText).data;
            data.forEach(item => {
                const tagClass = item.type === 'vip' ? 'vip' : 'normal';

                const movieTitle = item.movie?.title || `Movie ID: ${item.movie_id}`;
                const salleName = item.salle?.name || `Salle ID: ${item.salle_id}`;

                document.getElementById('ajax').innerHTML += `
                    <div class="card">
                        <h3>
                            🎬 ${movieTitle}
                            <span class="tag ${tagClass}">${item.type.toUpperCase()}</span>
                        </h3>
                        <p>🏢 Salle: ${salleName}</p>
                        <p>🗣️ Language: ${item.language}</p>
                        <p>🕓 Start: ${new Date(item.start_time).toLocaleString()}</p>
                        <p>🕘 End: ${item.end_time ? new Date(item.end_time).toLocaleString() : 'N/A'}</p>
                    </div>
                `;
            });
        }
    };

    xml.send();



    document.getElementById("createForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const form = e.target;
        const data = {
            movie_id: form.movie_id.value,
            salle_id: form.salle_id.value,
            type: form.type.value,
            language: form.language.value,
            start_time: form.start_time.value,
            end_time: form.end_time.value
        };

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://127.0.0.1:8000/api/seances", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Accept", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200 || xhr.status === 201) {
                    alert("Seance created ✅");
                    location.reload();
                } else {
                    alert("Failed to create ❌");
                    console.error(xhr.responseText);
                }
            }
        };

        xhr.send(JSON.stringify(data));
    });

    // DELETE Seance with XMLHttpRequest
    function deleteSeance(id) {
        if (!confirm("Are you sure you want to delete this seance?")) return;

        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", `http://127.0.0.1:8000/api/seances/${id}`, true);
        xhr.setRequestHeader("Accept", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200 || xhr.status === 204) {
                    alert("Seance deleted ✅");
                    location.reload();
                } else {
                    alert("Delete failed ❌");
                    console.error(xhr.responseText);
                }
            }
        };

        xhr.send();
    }
</script>

</body>
</html>
