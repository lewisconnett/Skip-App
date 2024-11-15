function toggleButtonState(button, isLoading, buttonText) {
    const spinner = button.querySelector('#spinner');
    const text = button.querySelector('#button-text');

    text.textContent = buttonText;
    if (isLoading) {
        spinner.classList.remove('visually-hidden');
        button.disabled = true;
    } else {
        spinner.classList.add('visually-hidden');
        button.disabled = false;
    }
    
}

window.addEventListener('load', async () => {
    const addItemForm = document.querySelector('form');

    document.querySelector('#show-form').addEventListener('click', function () {
        addItemForm.style.display = addItemForm.classList.toggle('d-none');
    });
});
