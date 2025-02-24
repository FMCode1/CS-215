// Function to validate topic name (non-blank and ≤256 characters)
function validateTopicName(topic) {
    return topic.trim() !== "" && topic.length <= 256;
}

// Function to handle form submission
function handleTopicSubmission(event) {
    const topicInput = document.getElementById("topic");
    const errorDiv = document.getElementById("error-text-text");
    let formIsValid = true;

    // Validate topic name
    if (!validateTopicName(topicInput.value)) {
        topicInput.classList.add("error");
        errorDiv.classList.remove("error-text-hidden");
        formIsValid = false;
    } else {
        topicInput.classList.remove("error");
        errorDiv.classList.add("error-text-hidden");
    }

    // Prevent submission if invalid
    if (!formIsValid) {
        event.preventDefault();
    } else {
        console.log("Topic submitted successfully.");
        // Optional: Submit data to the server here
    }
}

// Attach event listener after DOM loads
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".topic-form").addEventListener("submit", handleTopicSubmission);
});