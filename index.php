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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <title>SkipFind</title>
</head>

<body class="d-flex flex-column vh-100">
    <nav class="navbar bg-body-tertiary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                SkipFind
            </a>
            <div class="d-flex">
                <button class="btn ms-3" id="show-form">Add Item</button>
                <button class="btn ms-3">Info</button>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1">
        <div class="h-100 z-0" id="map">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Map loading...</span>
            </div>
        </div>
    </main>
    <form class="container d-flex flex-column justify-content-center bg-white d-none p-5 rounded position-absolute top-50 start-50 translate-middle" method="post">
        <h2>List an Item for Collection</h2>
        <div class="mb-3">
            <label class="form-label" for="iname">Item Name:</label>
            <input class="form-control" type="text" id="iname" name="iname" placeholder="Enter item name" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="idescription">Description:</label>
            <textarea class="form-control" type="text" id="idescription" name="idescription" placeholder="Enter description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="iimage" class="form-label" accepts="image/png, image/jpg, image/jpeg">Upload an Image: </label>
            <input class="form-control" type="file" id="iimage" name="iimage" required></input>
        </div>
        <button type="submit" class="btn btn-primary mb-3 mx-auto">List Item</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>