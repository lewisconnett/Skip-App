window.addEventListener('load', async () => {
    const locationTag = document.querySelector('#location-tag');
    const addItemForm = document.querySelector('#addItemForm');
    const location = await getUsersLocation();
    const locationName = await getLocationName(
        location.latitude,
        location.longitude
    );

    locationTag.textContent = locationName;

    document
        .querySelector('#add-item-icon')
        .addEventListener('click', function () {
            addItemForm.style.display =
                addItemForm.style.display === 'block' ? 'none' : 'block';
        });
});
