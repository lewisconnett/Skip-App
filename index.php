<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/css/styles.css">
    <link rel="stylesheet" href="leaflet/leaflet.css" />
    <script src="leaflet/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="js/map.js" defer></script>
    <script type="module" src="js/formhandler.js" defer></script>
    <script src="js/view.js" defer></script>

    <title>SkipFind</title>
</head>

<body>
    <main>
        <div id="map">Map loading...</div>
        <div class="top-overlay-content">
            <div id="location-tag-container">
                <svg id="location-ping-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-6" width="20" width="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <h2 id="location-tag">Fetching location...</h2>
            </div>
            <button id="settings-icon" class="overlay-buttons">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-6" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                </svg>
            </button>

        </div>
        <div class="bottom-overlay-content">
            <button id="add-item-icon" class="overlay-buttons">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-6" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>

        </div>
    </main>



    <form id="addItemForm" method="post">
        <h2>Add an Item for Collection</h2>
        <label for="iname">Item Name:</label>
        <input type="text" id="iname" name="iname" placeholder="Enter item name" required>
        <label for="idescription">Description:</label>
        <input type="text" id="idescription" name="idescription" placeholder="Enter description" required>
        <label for="iimage" accepts="image/png, image/jpg, image/jpeg">Upload an Image: </label>
        <input type="file" id="iimage" name="iimage" required></input>
        <button type="submit">Submit Item</button>
    </form>

</body>

</html>