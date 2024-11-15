const API_URL = 'https://lc1453.brighton.domains/SkipFind/includes/api.php';

async function submitFormData(formData) {
    try {
        const response = await axios.post(API_URL, formData);
        return response.data.status;
    } catch (error) {
        console.error('Error sending form data: ', error);
    }
}

function validateFormData(form) {
    const name = form.get('iname') || '';
    const description = form.get('idescription') || '';
    const image = form.get('iimage');

    return validateTextFields(name, description) && validateImage(image);
}

function validateTextFields(name, description) {
    if (!name.trim() || !description.trim()) {
        return false;
    }

    if (name.length > 100) {
        console.error('Name exceeds 100 characters');
        return false;
    }

    if (description.length > 255) {
        console.error('Description exceeds 255 characters');
        return false;
    }

    return true;
}

const acceptedTypes = ['image/jpeg', 'image/png'];
const maxImageSize = 5242880;

function validateImage(image) {
    if (!image) {
        console.error('No image provided');
        return false;
    }

    if (!acceptedTypes.includes(image.type)) {
        console.error('Invalid image type');
        return false;
    }

    if (image.size > maxImageSize) {
        console.error('Image size exceeds limit');
        return false;
    }
    return true;
}

window.addEventListener('load', () => {
    const form = document.querySelector('form');

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

        if (validateFormData(formData)) {
            try {
                const response = await submitFormData(formData);
                if (response == 'error') {
                    alert("Your item wasn't listed!");
                } else {
                    alert('Your item was listed!');
                }
                form.reset();
                location.reload();
            } catch (error) {
                alert('Something went wrong! Try again!');
                form.reset();
            }
        } else {
            console.log('Form is invalid, try again!');
            form.reset();
        }
    });
});
