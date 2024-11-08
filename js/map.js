const DEFAULT_LATITUDE = 50.8262;
const DEFAULT_LONGITUDE = -0.1356;

async function getUsersLocation() {
    if (navigator.geolocation) {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;

                    const userLocation = { latitude, longitude };
                    console.log(
                        'User location has been stored: ',
                        userLocation
                    );
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
        const map = L.map('map').setView([latitude, longitude], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);
        console.log('The map was successfully initialised');
        try {
            const items = await fetchObjects();
            addItemMarkers(items, map);
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
        const response = await fetch('includes/api.php');

        if (!response.ok) {
            throw new Error('Network response indicates a failure.');
        }
        return await response.json();
    } catch (error) {
        console.error('An issue was detected in the fetch operation: ', error);
        return [];
    }
}

function addItemMarkers(itemData, map) {
    for (var item of itemData) {
        if (item.latitude && item.longitude) {
            L.marker([item.latitude, item.longitude])
                .addTo(map)
                .bindPopup(`${item.name}<br>${item.description}`, {
                    className: 'marker-popup',
                });
        }
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
