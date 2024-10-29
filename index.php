<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>
    <script src="map.js" defer></script>

    <title>Skip App</title>
</head>

<body>
    <main>
        <!-- <div id="map"></div> -->
        <form action="includes/formhandler.inc.php" method="post">
            <label for="oname">Object Name:</label>
            <input type="text" id="oname" name="oname">
            <label for="odescription">Description:</label>
            <input type="text" id="odescription" name="odescription">
            <button type="submit">Submit</button>
        </form>
    </main>
</body>

</html>