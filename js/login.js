document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById('login-form');
    const errorMsg = document.getElementById('error-msg');
    const loginMessage = document.getElementById('loginMessage');

    if (!loginForm) {
        console.error("Login form not found!");
        return;
    }

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        console.log("Login form submitted"); //Debug

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const loginMessage = document.getElementById('loginMessage'); 

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

        loginMessage.style.color = "blue";
        loginMessage.textContent = "Logging in...";

       // Send login request to API - Updated to match your API structure
        fetch("../api.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            // credentials: 'include',
            body: JSON.stringify({
                type: 'Login', // Added type parameter as required by your API
                email: email,
                password: password
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("API Response:", data); // Debug
            
            if (data.status === 'success') 
            {
                // Store data in local storage - Updated to match your API response structure
                localStorage.setItem('apiKey', data.data.apiKey);
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('userId', data.data.userId);
                localStorage.setItem('userName', data.data.name);
                
                loginMessage.style.color = 'green';
                loginMessage.textContent = data.data.message || "Login successful. Redirecting...";
                
                // Redirect to products page after successful login
                setTimeout(() => {
                const userType = data.data.user_type;
                if (userType === 'Admin') {//this if is pointless because it take both customer and admin to the same place
                    window.location.href = '../php/products.php';//but the difference is that the admin will have more links in 
                } else {//header , i guess i will say that is the difference between customer and admin😊
                    window.location.href = '../php/products.php';
                }
            }, 1500);
                
            } 
            else 
            {
                // Handle error response - Updated to match your API error structure
                loginMessage.style.color = 'red';
                loginMessage.textContent = data.data || "Login failed. Please check your credentials.";
            }
        })
        .catch(err => {
            loginMessage.style.color = 'red';
            loginMessage.textContent = "An error occurred. Please try again.";
            console.error("Fetch error:", err); // Debug
        });
    });
});
