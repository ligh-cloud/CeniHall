<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seances Viewer</title>
</head>
<body>
<h1>This is a test page</h1>
<div id="ajax"></div>

<script>
    const xml = new XMLHttpRequest();
    xml.open("GET", "http://127.0.0.1:8000/api/seances", true); // match route

    xml.onreadystatechange = function () {
        if (xml.readyState === 4 && xml.status === 200) {
            let data = JSON.parse(xml.responseText).data;
            data.forEach(item => {
                document.getElementById('ajax').innerHTML += `
                        <div>
                            <p>🎬 Movie ID: ${item.movie_id}</p>
                            <p>🏢 Salle ID: ${item.salle_id}</p>
                            <p>🕓 Start: ${item.start_time}</p>
                            <p>🕘 End: ${item.end_time}</p>
                            <hr>
                        </div>
                    `;
            });
        }
    };

    xml.send();
</script>
</body>
</html>
