const API_URL = 'includes/api.php';

async function submitFormData(formData) {
    fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

window.addEventListener('load', () => {
    const form = document.querySelector('#addItemForm');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const itemName = form.elements['iname'].value;
        const itemDescription = form.elements['idescription'].value;

        let usersLocation;
        try {
            usersLocation = await getUsersLocation();
        } catch (error) {
            console.error("Error gathering user's location: ", error);
            alert('There was a problem retrieving your location.');
            usersLocation = {
                latitude: DEFAULT_LATITUDE,
                longitude: DEFAULT_LONGITUDE,
            };
        }

        const formData = {
            name: itemName,
            description: itemDescription,
            latitude: usersLocation.latitude,
            longitude: usersLocation.longitude,
        };

        form.querySelector('button[type="submit"]').disabled = true;

        try {
            const result = await submitFormData(formData);
            alert('Record created.');
        } catch (error) {
            alert('An error occurred while submitting. Please try again.');
        } finally {
            form.querySelector('button[type="submit"]').disabled = false;
            location.reload();
        }
    });
});
