document.addEventListener("DOMContentLoaded", function() {
    const signInButton = document.getElementById('signInButton');
    const signUpButton = document.getElementById('signUpButton');
    const signInForm = document.getElementById('signIn');
    const signUpForm = document.getElementById('signup');

    signInButton.addEventListener('click', function() {
        signInForm.style.display = 'block';
        signUpForm.style.display = 'none';
    });

    signUpButton.addEventListener('click', function() {
        signInForm.style.display = 'none';
        signUpForm.style.display = 'block';
    });

    // Initial form state
    if (window.location.hash === '#signup') {
        signUpForm.style.display = 'block';
        signInForm.style.display = 'none';
    } else {
        signInForm.style.display = 'block';
        signUpForm.style.display = 'none';
    }
});
