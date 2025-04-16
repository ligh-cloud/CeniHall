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
    </style>
</head>
<body>

<h1>🎬 All Seances</h1>
<div id="ajax"></div>

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
</script>

</body>
</html>
