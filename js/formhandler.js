window.addEventListener('load', () => {
    const form = document.querySelector('#addItemForm');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const usersLocation = await getUsersLocation();

        const itemName = form.elements['iname'].value;
        const itemDescription = form.elements['idescription'].value;
        const itemLatitude = usersLocation.latitude;
        const itemLongitude = usersLocation.longitude;

        // TODO: Capture users location when they submit the form

        console.log(itemName, itemDescription, itemLatitude, itemLongitude);

        //TODO: Validate and sanitise inputs on client side

        const formData = {
            name: itemName,
            description: itemDescription,
            latitude: itemLatitude,
            longitude: itemLongitude,
        };

        fetch('includes/api.php', {
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
            .then((result) => {
                if (result.status === 'success') {
                    alert(result.message);
                    location.reload();
                } else {
                    alert(result.error);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
});
