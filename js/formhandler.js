const API_URL = 'includes/api.php';

async function submitFormData(formDataToSend) {
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            body: formDataToSend,
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Error:', errorData.error);
            throw new Error(errorData.error || 'Network response was not ok');
        }
        const result = await response.json();
        console.log('Success:', result);
        return result;
    } catch (error) {
        throw new Error('Error: ', error);
    }
}

window.addEventListener('load', () => {
    const form = document.querySelector('#addItemForm');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const itemName = form.elements['iname'].value;
        const itemDescription = form.elements['idescription'].value;
        const imageFile = form.elements['iimage'].files[0];

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

        const formData = new FormData();
        formData.append('name', itemName);
        formData.append('description', itemDescription);
        formData.append('latitude', locationCoordinates.latitude);
        formData.append('longitude', locationCoordinates.longitude);
        formData.append('imageFile', imageFile);

        const submitButton = form.querySelector('button[type="submit"]');

        submitButton.disabled = true;

        try {
            await submitFormData(formData);
            alert("Item added successfully!");
            form.reset();
        } catch (error) {
            alert('An error occurred while submitting. Please try again.');
        } finally {
            submitButton.disabled = false;
        }
    });
});
