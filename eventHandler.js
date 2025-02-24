// Function to validate email format
function validateEmail(email) {
    let emailRegEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegEx.test(email);
}

// Function to validate screen name 
function validateScreenName(sname) {
    let snameRegEx = /^[a-zA-Z0-9]+$/;
    return snameRegEx.test(sname);
}

// Function to check if an avatar file is selected
function validateAvatar(avatar) {
    return avatar.files.length > 0;
}

// Function to validate password 
function validatePassword(pwd) {
    let passwordRegEx = /^(?=.*[^a-zA-Z]).{6,}$/;
    return passwordRegEx.test(pwd);
}

// Function to validate if passwords match
function validatePasswordMatch(pwd, cpwd) {
    return pwd === cpwd;
}

// Function to show error message
function showError(input, errorId) {
    input.classList.add("error");
    document.getElementById(errorId).classList.remove("error-text-hidden");
}

// Function to remove error message
function hideError(input, errorId) {
    input.classList.remove("error");
    document.getElementById(errorId).classList.add("error-text-hidden");
}

// Function to handle form submission and validation
function validateSignup(event) {
    let email = document.getElementById("email");
    let sname = document.getElementById("sname");
    let pwd = document.getElementById("pwd");
    let cpwd = document.getElementById("cpassword");
    let avatar = document.getElementById("avatar");

    let formIsValid = true;

    // Email Validation
    if (!validateEmail(email.value)) {
        showError(email, "error-text-email");
        formIsValid = false;
    } else {
        hideError(email, "error-text-email");
    }

    // Screen Name Validation
    if (!validateScreenName(sname.value)) {
        showError(sname, "error-text-sname");
        formIsValid = false;
    } else {
        hideError(sname, "error-text-sname");
    }

    
   // Avatar Validation
if (!validateAvatar(avatar)) {
    avatar.classList.add("error");
    document.getElementById("error-file-avatar").classList.remove("error-file-hidden"); 
    formIsValid = false;
} else {
    avatar.classList.remove("error");
    document.getElementById("error-file-avatar").classList.add("error-file-hidden");
}

    // Password Validation
    if (!validatePassword(pwd.value)) {
        showError(pwd, "error-text-pwd");
        formIsValid = false;
    } else {
        hideError(pwd, "error-text-pwd");
    }

    // Confirm Password Validation
    if (!validatePasswordMatch(pwd.value, cpwd.value)) {
        showError(cpwd, "error-text-cpassword");
        formIsValid = false;
    } else {
        hideError(cpwd, "error-text-cpassword");
    }

    // Prevent form submission if validation fails
    if (!formIsValid) {
        event.preventDefault();
    } else {
        console.log("Signup successful, sending data to the server.");
    }
}
