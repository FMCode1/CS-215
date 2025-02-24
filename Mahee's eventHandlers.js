function validateUsername(email) {
	let unameRegEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

	if (unameRegEx.test(email))
		return true;
	else
		return false;
}
function validatePWD(password) 
{
    let passwordRegEx = /^(?=.*[^a-zA-Z]).{6,}$/;
	if (password.length >= 6 && passwordRegEx.test(password))
		return true;
	else
		return false;
}
function validateLogin(event) {

	let email = document.getElementById("email");
	let emailErr = document.getElementById("error-text-email");
	let password = document.getElementById("password");
	let passwordErr = document.getElementById("error-text-password");
	let formIsValid = true;

	if (!validateUsername(email.value)) {
		// Comment the line below
		console.log("'" + email.value + "' is not a valid username");
		//	To Do 7a: ADD your code to dynamically add a class name to <input> tag to highlight the input box.	
		email.classList.add("invalid");
		//	To Do 7c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		emailErr.classList.remove("hidden");
		formIsValid = false;
	}
	//	An else block to remove the error messages and the styles when the input field passes the validation 
	else {

		//	To Do 7b: ADD your code to dynamically remove a class name from the <input> tag to remove the highlights from the input box. 
		email.classList.remove("invalid");
		//	To Do 7d: ADD your code to dynamically add a class name from the <p> tag to hide the error message.	
		emailErr.classList.add("hidden");
	}

	if (!validatePWD(password.value)) {
		// Comment the line below
		console.log("'" + password.value + "' is not a valid password");
		//	To Do 7a: ADD your code to dynamically add a class name to <input> tag to highlight the input box.	
		password.classList.add("invalid");
		//	To Do 7c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		passwordErr.classList.remove("hidden");
		formIsValid = false;
	}
	//	An else block to remove the error messages and the styles when the input field passes the validation 
	else {

		//	To Do 7b: ADD your code to dynamically remove a class name from the <input> tag to remove the highlights from the input box. 
		password.classList.remove("invalid");
		//	To Do 7d: ADD your code to dynamically add a class name from the <p> tag to hide the error message.	
		passwordErr.classList.add("hidden");
	}
	if (formIsValid === false) {
		event.preventDefault();

	}
	else {
		console.log("Validation successful, sending data to the server");
	}
}
function unameHandler(event) {
	let email = event.target;
    let emailErr = document.getElementById("error-text-emails");
	if (!validateUsername(uname.value)) {
		// Comment the line below
		console.log("Username '" + email.value + "' is not valid.");
		//	To Do 8a: ADD your code to dynamically add a class name to <input> tag to highlight the input box.	
		email.classList.add("invalid");
		//	To Do 8c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		emailErr.classList.remove("hidden");
	}
	else {
		// Comment the line below
		console.log("Username is valid.");
		//	To Do 8b: ADD your code to dynamically remove a class name from the <input> tag to remove the highlights from the input box. 
		email.classList.remove("invalid");
		//	To Do 8d: ADD your code to dynamically add a class name from the <p> tag to hide the error message.	
		emailErr.classList.add("hidden");
	}
}
function passwordHandler(event) {
	let password = event.target;

	if (!validatePWD(password.value)) {
		console.log("Password '" + password.value + "' is not valid.");
	}
	else {
		console.log("Password is valid.");
	}
}


