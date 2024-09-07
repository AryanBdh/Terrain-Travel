document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('signin-form');

    form.addEventListener('submit', (event) => {
        // Get form fields
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        // Clear previous error messages
        const errorMessage = document.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.textContent = '';
        }

        // Simple validation
        if (email === '' || password === '') {
            event.preventDefault(); // Prevent form submission
            showError('Please fill in all fields.');
        } else if (!validateEmail(email)) {
            event.preventDefault(); // Prevent form submission
            showError('Please enter a valid email address.');
        }
    });

    function validateEmail(email) {
        // Simple email regex for basic validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showError(message) {
        const form = document.getElementById('signin-form');
        const errorDiv = document.createElement('p');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        form.insertBefore(errorDiv, form.firstChild);
    }
});
