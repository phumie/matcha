<?php

    $lat = $_POST['lat'];
    $long = $_POST['long'];

    echo $lat.' '.$long;

    
?>

<html>
    <body>

    <p>Click the button to get your coordinates.</p>
    

    <form action="" method="post">
        <input type="hidden" name="lat">
        <input type="hidden" name="long">
        <button onclick="getLocation()">Try It</button>
    </form>

    


    <p id="demo"></p>

    <script>
    var x = document.getElementById("demo");

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function sendPosition(position) {
        var lat = position.coords.latitude;
        var long = position.coords.longitude;

        document.elementById('lat').value = lat;
        document.elementById('long').value = long;
    }
    </script>

    </body>
</html>
