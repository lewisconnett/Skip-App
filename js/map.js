let map = L.map('map').setView([50.8229, -0.13947], 16);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution:
        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

async function fetchObjects() {
    try {
        const response = await fetch(
            'includes/api.php'
        );
        
        if (!response.ok) {
            throw new Error('Network response indicates a failure.');
        }
        const objectData = await response.json();

        objectData.forEach((item) => {
            if (item.latitude && item.longitude) {
                L.marker([item.latitude, item.longitude])
                    .addTo(map)
                    .bindPopup(`${item.name}<br>${item.description}`, {
                        className: 'marker-popup'
                    });
            }
        });

    } catch (error) {
        console.error('An issue was detected in the fetch operation: ', error);
    }
}

fetchObjects();
