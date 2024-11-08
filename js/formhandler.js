window.addEventListener('load', () => {
    const form = document.querySelector('#addItemForm');

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const itemName = form.elements['iname'].value;
        const itemDescription = form.elements['idescription'].value;

        // TODO: Capture users location when they submit the form

        /* 
        
        1. Get current position using navigator

        2. Success Call back function -> Calls a function that creates the form data

        3. Error Call back function -> Calls a function 

        
        */

        const itemLatitude = form.elements['ilatitude'].value;
        const itemLongitude = form.elements['ilongitude'].value;

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
