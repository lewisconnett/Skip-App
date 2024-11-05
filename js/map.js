const successCallBack = (position) => {
    console.log('Successfully retrieved user location:', position.coords);
    initMap(position.coords.latitude, position.coords.longitude);
};

const errorCallBack = (error) => {
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
    initMap(50.8262, -0.1356);
};

navigator.geolocation.getCurrentPosition(successCallBack, errorCallBack);

async function initMap(latitude, longitude) {
    try {
        let map = L.map('map').setView([latitude, longitude], 14);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        await fetchObjects(map);

        console.log('The map was initialised successfully!');
    } catch (error) {
        console.error(
            'An error occured when trying to initialise the map: ',
            error
        );
    }
}

async function fetchObjects(map) {
    try {
        const response = await fetch('includes/api.php');

        if (!response.ok) {
            throw new Error('Network response indicates a failure.');
        }
        const objectData = await response.json();

        objectData.forEach((item) => {
            if (item.latitude && item.longitude) {
                L.marker([item.latitude, item.longitude])
                    .addTo(map)
                    .bindPopup(`${item.name}<br>${item.description}`, {
                        className: 'marker-popup',
                    });
            }
        });
    } catch (error) {
        console.error('An issue was detected in the fetch operation: ', error);
    }
}
