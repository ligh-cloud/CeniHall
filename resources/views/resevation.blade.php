<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Salle Dashboard</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: auto; padding: 20px; }
        input, button { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #2c7be5; color: white; border: none; cursor: pointer; }
        button:hover { background: #1a5ac8; }
        .salle { border: 1px solid #ccc; padding: 10px; margin-top: 10px; border-radius: 4px; }
    </style>
</head>
<body>

<h1>Salle Dashboard (AJAX version)</h1>

<!-- Form Inputs -->
<input type="text" id="salleName" placeholder="Salle name">
<input type="number" id="capacity" placeholder="Capacity">
<input type="number" id="siegeCount" placeholder="Number of sieges to create">
<button onclick="createSalle()">Create Salle & Sieges</button>

<!-- Salles List -->
<h2>All Salles</h2>
<div id="salleList"></div>

<script>
    const salleApi = "http://127.0.0.1:8000/api/salles";
    const siegeApi = "http://127.0.0.1:8000/api/sieges";
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function getRequest(url, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                callback(JSON.parse(xhr.responseText));
            }
        };
        xhr.send();
    }

    function postRequest(url, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("X-CSRF-TOKEN", csrf);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                callback(xhr);
            }
        };
        xhr.send(JSON.stringify(data));
    }

    function deleteRequest(url, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", url, true);
        xhr.setRequestHeader("X-CSRF-TOKEN", csrf);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                callback(xhr);
            }
        };
        xhr.send();
    }

    function fetchSalles() {
        getRequest(salleApi, function(data) {
            const salles = data.data;
            const list = document.getElementById("salleList");
            list.innerHTML = "";
            salles.forEach(salle => {
                const div = document.createElement("div");
                div.className = "salle";
                div.innerHTML = `
          <strong>${salle.name}</strong> (Capacity: ${salle.capacity})
          <br>
          <button onclick="deleteSalle(${salle.id})">Delete</button>
        `;
                list.appendChild(div);
            });
        });
    }

    function createSalle() {
        const name = document.getElementById("salleName").value.trim();
        const capacity = parseInt(document.getElementById("capacity").value.trim());
        const siegeCount = parseInt(document.getElementById("siegeCount").value.trim());

        if (!name || capacity <= 0 || siegeCount <= 0) {
            alert("Please enter valid data.");
            return;
        }

        postRequest(salleApi, { name, capacity }, function(res) {
            if (res.status === 200 || res.status === 201) {
                const salle = JSON.parse(res.responseText).salle;
                // Create Sieges
                for (let i = 1; i <= siegeCount; i++) {
                    postRequest(siegeApi, {
                        salle_id: salle.id,
                        number: i
                    }, function(siegeRes) {
                        if (siegeRes.status !== 200 && siegeRes.status !== 201) {
                            console.error("Failed to create siege", siegeRes.responseText);
                        }
                    });
                }
                alert("Salle and sieges created!");
                document.getElementById("salleName").value = "";
                document.getElementById("capacity").value = "";
                document.getElementById("siegeCount").value = "";
                setTimeout(fetchSalles, 500);
            } else {
                alert("Failed to create salle.");
                console.error(res.responseText);
            }
        });
    }

    function deleteSalle(id) {
        if (!confirm("Are you sure?")) return;
        deleteRequest(`${salleApi}/${id}`, function(res) {
            if (res.status === 200) {
                alert("Salle deleted.");
                fetchSalles();
            } else {
                alert("Failed to delete salle.");
            }
        });
    }

    window.onload = fetchSalles;
</script>

</body>
</html>
