let map = L.map('map').setView([50.8229, -0.13947], 16);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution:
        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

L.marker([50.8223, -0.13236]).addTo(map);
L.marker([50.8583, -0.13211]).addTo(map);

//TODO: Create function that adds markers to the map

const addMarkerBtn = document.querySelector('#addItem');

addMarkerBtn.addEventListener('click', () => {
    let latCoordinate = Math.floor(Math.random() * 1) + 50;
    let longCoordinate = Math.floor(Math.random() * 1);

    let newMarker = L.marker([latCoordinate, -longCoordinate]);

    console.log(newMarker);
    newMarker.addTo(map);
});
