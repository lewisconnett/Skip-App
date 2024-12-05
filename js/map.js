const DEFAULT_LATITUDE = 50.8262;
const DEFAULT_LONGITUDE = -0.1356;

let map;

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

    map = L.map('map', {
        zoomControl: false,
    }).setView([latitude, longitude], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    try {
        const availableItems = await fetchObjects();
        availableItems.data.forEach(item => {
            addMarkerToMap(item);
        });
        
    } catch (error) {
        console.error(
            'There was an error adding the item markers to the map: ',
            error
        );
    }
}

function addMarkerToMap(item) {
    let customMarker = L.icon({
        iconUrl: 'assets/icons/marker-icon.svg',
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
    });

    const popupContent = `
        <div class="card" style="width: 18rem;">
            <img src="uploads/${item.stored_filename}" class="card-img-top" alt="${item.name}">
            <div class="card-body">
                <h5 class="card-title">${item.name}</h5>
                <p class="card-text">${item.description}</p>
                <button data-item-id="${item.id}" class="btn btn-primary text-white claim-item-btn">Claim Item</button>
            </div>
        </div>
    `;

    L.marker([item.latitude, item.longitude], { icon: customMarker })
        .addTo(map)
        .bindPopup(popupContent, { className: 'marker-popup' });
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

document.addEventListener('click', async function (event) {
    if (event.target && event.target.classList.contains('claim-item-btn')) {
        event.preventDefault();
        const itemId = event.target.getAttribute('data-item-id');
        if (itemId) {
            try {
                const response = await axios.put(
                    `https://lc1453.brighton.domains/SkipFind/includes/api.php?item_id=${itemId}`
                );
                console.log(response.data);
                event.target.setAttribute('disabled', 'true');
                event.target.innerText = 'Item Claimed';
                location.reload();
            } catch (error) {
                console.error('Error updating item availability', error);
            }
        }
    }
});

async function start() {
    try {
        const location = await getUsersLocation();
        initMap(location.latitude, location.longitude);
    } catch (error) {
        initMap(error.latitude, error.longitude);
    }
}

start();
