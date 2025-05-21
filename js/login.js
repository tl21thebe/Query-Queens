document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById('login-form');

    if (!loginForm) {
        console.error("Login form not found!");
        return;
    }

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        console.log("Login form submitted"); //Debug

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const errorMsg = document.getElementById('error-msg');

        // Basic validation
        if (!email || !password) {
            errorMsg.textContent = "Please fill in all fields.";
            errorMsg.style.color = "red";
            return;
        }

        // Email format validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMsg.textContent = "Invalid email format.";
            errorMsg.style.color = "red";
            return;
        }

        errorMsg.style.color = "black";
        errorMsg.textContent = "Logging in...";

        // Send login request to API
        fetch('api.php?type=Login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email,
                password
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log("API Response:", data); // Debug

            if (data.status === 'success') {
                // Store data in local storage
                localStorage.setItem('apiKey', data.user.api_key);
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('type', data.user.type);
                localStorage.setItem('userName', data.user.name);

                errorMsg.style.color = 'green';
                errorMsg.textContent = "Login successful. Redirecting...";

                setTimeout(() => {
                    window.location.href = 'products.php';
                }, 1500);
                
            } else {
                errorMsg.style.color = 'red';

                if (data.error === 'User not registered') {
                    errorMsg.textContent = "This email is not registered. Please sign up first.";
                } else if (data.error) {
                    errorMsg.textContent = data.error;
                } else if (data.message) {
                    errorMsg.textContent = data.message;
                } else {
                    errorMsg.textContent = "Login failed. Please check your credentials.";
                }
            }
        })
        .catch(err => {
            errorMsg.style.color = 'red';
            errorMsg.textContent = "An error occurred. Please try again.";
            console.error("Fetch error:", err); //Debug
        });
    });
});
