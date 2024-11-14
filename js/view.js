window.addEventListener('load', async () => {
    const addItemForm = document.querySelector('form');

    document
        .querySelector('#show-form')
        .addEventListener('click', function () {
            addItemForm.style.display =
                addItemForm.classList.toggle('d-none');
        });
});
