window.addEventListener('load', async () => {
    const addItemForm = document.querySelector('form');

    document
        .querySelector('#add-item-icon')
        .addEventListener('click', function () {
            addItemForm.style.display =
                addItemForm.classList.toggle('d-none');
        });
});
