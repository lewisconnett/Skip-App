<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>
    <script src="js/map.js" defer></script>

    <title>Skip App - In Development</title>
</head>

<body>
    <main>
        <h1 style="color: #ff6600;">Skip App - Development Version</h1>
        <div class="map-container">
            <div id="map">Loading map...</div>
            <form action="includes/formhandler.php" method="post">
                <h2>Add an Item</h2>
                <label for="iname">Item Name:</label>
                <input type="text" id="iname" name="iname" placeholder="Enter item name">
                <label for="idescription">Description:</label>
                <input type="text" id="idescription" name="idescription" placeholder="Enter description">
                <label for="ilatitude">Latitude:</label>
                <input type="text" id="ilatitude" name="ilatitude" placeholder="Enter latitude">
                <label for="ilongitude">Longitude:</label>
                <input type="text" id="ilongitude" name="ilongitude" placeholder="Enter longitude">
                <button type="submit">Submit Item</button>
            </form>
        </div>

    </main>
</body>

</html>