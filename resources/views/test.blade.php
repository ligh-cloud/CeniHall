<h1 >this is a test page</h1>
<p id="ajax"></p>
<script>
    const xml = new XMLHttpRequest();
    xml.open("get" , "http://127.0.0.1:8000/api/movie" , true);
    xml.onreadystatechange = function () {
        if(xml.readyState === 4 && xml.status === 200){
            let data = JSON.parse(xml.responseText).data
            data.forEach(item => {
                document.getElementById('ajax').innerHTML += `<p>${item.title}</p>`
            })
            console.log(data)
        }

    }

        xml.send();
</script>
