/**
 * Toggles the state of a button to indicate loading or normal state
 * @param {HTMLElement} button - The button element to toggle
 * @param {boolean} isLoading - Whether the button should be in a loading state
 * @param {string} buttonText - The text to display on the button
 */
export function toggleButtonState(button, isLoading, buttonText) {
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

/**
 * Displays a Bootstrap toast notification with a specified message
 * @param {string} message - The message to display in the toast
 */
export function showToast(message) {
    const toastLiveExample = document.querySelector('#liveToast');

    document.querySelector('#toast-body').textContent = message;

    const toastBootstrap =
        bootstrap.Toast.getOrCreateInstance(toastLiveExample);

    toastBootstrap.show();
}

window.addEventListener('load', async () => {
    const addItemForm = document.querySelector('form');

    document.querySelector('#show-form').addEventListener('click', () => {
        addItemForm.style.display = addItemForm.classList.toggle('d-none');
    });

    document.querySelector('#close-form').addEventListener('click', () => {
        addItemForm.style.display = addItemForm.classList.add('d-none');
    });
});
