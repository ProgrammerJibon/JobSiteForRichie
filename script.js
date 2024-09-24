// script.js

// Front-end validation for the login form
function validateLogin() {
    const email = document.forms["loginForm"]["email"].value;
    const password = document.forms["loginForm"]["password"].value;

    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert("Please provide a valid email address.");
        return false;
    }
    if (password.length < 8) {
        alert("Password must be at least 8 characters.");
        return false;
    }
    return true;
}

// Front-end validation for the registration form
function validateRegistration() {
    const email = document.forms["registerForm"]["email"].value;
    const password = document.forms["registerForm"]["password"].value;
    const fullName = document.forms["registerForm"]["full_name"].value;

    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert("Please provide a valid email address.");
        return false;
    }
    if (password.length < 8 || password.length > 64) {
        alert("Password must be between 8 and 64 characters.");
        return false;
    }
    if (fullName.length < 3 || fullName.length > 50) {
        alert("Full name must be between 3 and 50 characters.");
        return false;
    }
    return true;
}

// Front-end validation for the job application form
function validateApplication() {
    const cv = document.forms["applyForm"]["cv"].value;
    const ext = cv.split('.').pop().toLowerCase();
    if (ext !== "pdf") {
        alert("Please upload a valid CV in PDF format.");
        return false;
    }
    return true;
}
