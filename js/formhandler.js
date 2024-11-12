const API_URL = 'https://lc1453.brighton.domains/skipapp/includes/api.php';

async function submitFormData(formData) {
    try {
        const response = await axios.post(API_URL, formData);

        console.log(response.data);
    } catch (error) {
        console.error('Error sending form data: ', error);
    }
}

window.addEventListener('load', () => {
    const form = document.querySelector('#addItemForm');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);

        let locationCoordinates;
        try {
            locationCoordinates = await getUsersLocation();
        } catch (error) {
            console.error("Error gathering user's location: ", error);
            alert('There was a problem retrieving your location.');
            locationCoordinates = {
                latitude: DEFAULT_LATITUDE,
                longitude: DEFAULT_LONGITUDE,
            };
        }

        formData.append('ilatitude', locationCoordinates.latitude);
        formData.append('ilongitude', locationCoordinates.longitude);

        try {
            submitFormData(formData);
            alert("Your item has been listed");
            form.reset();
            location.reload();
        } catch (error) {
            alert("Something went wrong! Try again!");
            form.reset();
        }
    });
});
