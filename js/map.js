const DEFAULT_LATITUDE = 50.8262;
const DEFAULT_LONGITUDE = -0.1356;

async function getUsersLocation() {
    if (navigator.geolocation) {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;

                    const userLocation = { latitude, longitude };
                    resolve(userLocation);
                },
                (error) => {
                    handleGeolocationErrors(error);
                    reject({
                        latitude: DEFAULT_LATITUDE,
                        longitude: DEFAULT_LONGITUDE,
                    });
                }
            );
        });
    } else {
        console.error('Geolocation is not supported by this browser.');
        return { latitude: DEFAULT_LATITUDE, longitude: DEFAULT_LONGITUDE };
    }
}

function handleGeolocationErrors(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            alert('User denied the request for Geolocation.');
            break;
        case error.POSITION_UNAVAILABLE:
            alert('Location information is unavailable.');
            break;
        case error.TIMEOUT:
            alert('The request to get user location timed out.');
            break;
        case error.UNKNOWN_ERROR:
            alert('An unknown error occurred.');
            break;
    }
}

async function initMap(latitude, longitude) {
    try {
        const map = L.map('map', {
            zoomControl: false,
        }).setView([latitude, longitude], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);
        try {
            const availableItems = await fetchObjects();
            addItemMarkers(availableItems.data, map);
        } catch (error) {
            console.error(
                'There was an error adding the item markers to the map: ',
                error
            );
        }
    } catch (error) {
        console.error(
            'An error occured when trying to initialise the map: ',
            error
        );
    }
}

async function fetchObjects() {
    try {
        const response = await axios.get(
            'https://lc1453.brighton.domains/SkipFind/includes/api.php'
        );
        return response.data;
    } catch (error) {
        console.error('Error fetching objects: ', error);
        return [];
    }
}

function addItemMarkers(items, map) {
    let customMarker = L.icon({
        iconUrl: 'assets/icons/marker-icon.svg',
    });
    for (var item of items) {
        if (item.latitude && item.longitude) {
            let popupContent = `<div class="card" style="width: 18rem;">
  <img src="uploads/${item.stored_filename}" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">${item.name}</h5>
    <p class="card-text">${item.description}</p>
    <a href="#" class="btn btn-primary text-white">View Item</a>
  </div>
</div>`;
            L.marker([item.latitude, item.longitude], { icon: customMarker })
                .addTo(map)
                .bindPopup(popupContent, {
                    className: 'marker-popup',
                });
        }
    }
}

async function getLocationName(latitude, longitude) {
    if (!latitude || !longitude) {
        console.error(
            'Longitude and latitude coordinates are empty or invalid'
        );
        return null;
    }

    try {
        const response = await axios.get(
            `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`
        );
        const data = response.data;

        if (data && data.address) {
            const address = data.address;
            const locationName = address.suburb || 'Location not found';
            return locationName;
        } else {
            console.error('Location not found in the response');
            return null;
        }
    } catch (error) {
        console.error('Error fetching location:', error);
        return null;
    }
}

async function start() {
    try {
        const location = await getUsersLocation();
        initMap(location.latitude, location.longitude);
    } catch (error) {
        initMap(error.latitude, error.longitude);
    }
}

start();
