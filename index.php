<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles/css/styles.css">
    <link rel="stylesheet" href="leaflet/leaflet.css">
    <script src="leaflet/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <title>Skip App</title>
</head>

<body class="d-flex flex-column vh-100">
    <nav class="navbar bg-body-tertiary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                Skip App
            </a>
            <div class="d-flex">
                <button class="btn ms-3 btn-primary " id="show-form">Add Item</button>
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
    <form style="max-width: 700px;" class="shadow container d-flex flex-column justify-content-center bg-white d-none p-5 rounded position-absolute top-50 start-50 translate-middle mw-700" method="post" id="item-form">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>List an Item for Collection</h2>
            <button type="button" class="btn-close" id="close-form" aria-label="Close"></button>
        </div>
        <div class="mb-3">
            <label class="form-label" for="iname">Item Name:</label>
            <input class="form-control" type="text" id="iname" name="iname" placeholder="Enter item name" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="idescription">Description:</label>
            <textarea class="form-control" id="idescription" name="idescription" placeholder="Enter description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="iimage" class="form-label">Upload an Image: </label>
            <input class="form-control" type="file" id="iimage" name="iimage" required>
        </div>
        <button type="submit" class="btn btn-primary mx-auto mb-3">
            <span class="spinner-border spinner-border-sm visually-hidden" id="spinner" aria-hidden="true"></span>
            <span id="button-text" role="status">List Item</span>
        </button>
    </form>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Skip App</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div id="toast-body" class="toast-body" aria-live="polite">
                Notification will appear here.
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="src/js/map.js"></script>
    <script type="module" src="src/js/formhandler.js"></script>
    <script type="module" src="src/js/view.js"></script>
</body>

</html>