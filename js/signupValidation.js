document.getElementById('signup-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const surname = document.getElementById('surname').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const type = document.getElementById('type').value;
    const errorMsg = document.getElementById('error-msg');

    // Regex for validations
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/;

    // Validation checks
    if (!name || !surname || !email || !password || !type) {
        errorMsg.textContent = "Please fill in all fields.";
        return;
    }

    if (!emailRegex.test(email)) {
        errorMsg.textContent = "Invalid email address.";
        return;
    }

    if (!passwordRegex.test(password)) {
        errorMsg.textContent = "Password must be at least 9 characters and include uppercase, lowercase, a number, and a special character.";
        return;
    }

    // Clear previous message and show a processing notice
    errorMsg.style.color = "black";
    errorMsg.textContent = "Submitting registration...";

    fetch('api.php?endpoint=signup', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name,
            surname,
            email,
            password,
            type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            errorMsg.style.color = 'green';
            errorMsg.textContent = "Registration successful. Redirecting to login...";
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 2000);
        } else {
            errorMsg.style.color = 'red';

            if (data.data?.message?.includes("Email already exists")) {
                errorMsg.textContent = "This email is already registered. Please use a different email or log in.";
            } else if (data.data?.message) {
                errorMsg.textContent = data.data.message;
            } else {
                errorMsg.textContent = "Registration failed. Please try again.";
            }
        }
    })
    .catch(err => {
        errorMsg.style.color = 'red';
        errorMsg.textContent = "An error occurred. Please try again.";
        console.error(err);
    });
});
