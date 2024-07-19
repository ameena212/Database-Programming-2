document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.querySelector('form');
    signupForm.addEventListener('submit', (event) => {
        const password = document.getElementById('password').value;
        if (password.length < 6) {
            alert('Password must be at least 6 characters long.');
            event.preventDefault();
        }
    });
});
