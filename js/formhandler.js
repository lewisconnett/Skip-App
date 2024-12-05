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
    const name = form.get('iname').trim() || '';
    const description = form.get('idescription').trim() || '';
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

        const button = event.target.querySelector('[type="submit"]');

        toggleButtonState(button, true, 'Listing Item');

        const formData = new FormData(form);

        let locationCoordinates = await getUsersLocation();

        formData.append('ilatitude', locationCoordinates.latitude);
        formData.append('ilongitude', locationCoordinates.longitude);

        validateFormData(formData);

        if (validateFormData(formData)) {
            try {
                const response = await axios.post(API_URL, formData);
                if (
                    response.data.record_insertion &&
                    response.data.record_insertion.status === 'success'
                ) {
                    toggleButtonState(button, false, 'Item Listed');
                    const newItem = response.data.record_insertion.data;
                    addMarkerToMap(newItem);
                    form.reset();
                    alert('Item added successfully!');
                } else {
                    console.error('Error adding item:', response.data);
                    alert('Failed to add item!');
                }
            } catch (error) {
                console.error(
                    'Error adding item:',
                    error.response?.data || error.message
                );
                alert('Something went wrong. Please try again.');
            }


        } else {
            console.log('Form is invalid, try again!');
            form.reset();
        }
    });
});
