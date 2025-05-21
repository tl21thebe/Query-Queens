document.addEventListener('DOMContentLoaded', function() 
{
    const form = document.getElementById('signup-form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Password validation feedback
    passwordInput.addEventListener('input', function() 
    {
        validatePasswordRequirements(this.value);
    });
    
    // Form submission
    form.addEventListener('submit', function(e) 
    {
        e.preventDefault();
        
        // Get form fields
        const name = document.getElementById('name').value;
        const surname = document.getElementById('surname').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const userType = document.getElementById('type').value;
        
        // Reset error messages
        clearErrors();
        
        // Basic validation
        let isValid = true;
        
        if (!name.trim()) 
        {
            showError('name-error', 'Name is required');
            isValid = false;
        }
        
        if (!surname.trim()) 
        {
            showError('surname-error', 'Surname is required');
            isValid = false;
        }
        
        if (!email.trim()) 
        {
            showError('email-error', 'Email is required');
            isValid = false;
        } 
        else if (!isValidEmail(email)) 
        {
            showError('email-error', 'Please enter a valid email address');
            isValid = false;
        }
        
        if (!password.trim()) 
        {
            showError('password-error', 'Password is required');
            isValid = false;
        } 
        else if (!isValidPassword(password)) 
        {
            showError('password-error', 'Password does not meet requirements');
            isValid = false;
        }
        
        if (password !== confirmPassword) 
        {
            showError('confirm-password-error', 'Passwords do not match');
            isValid = false;
        }
        
        if (isValid) 
        {
            // If all validation passes, register the user
            registerUser(name, surname, email, password, userType);
        }
    });
    
    function validatePasswordRequirements(password) 
    {
        const length = document.getElementById('length');
        const uppercase = document.getElementById('uppercase');
        const lowercase = document.getElementById('lowercase');
        const number = document.getElementById('number');
        const symbol = document.getElementById('symbol');
        
        // Reset classes
        length.className = '';
        uppercase.className = '';
        lowercase.className = '';
        number.className = '';
        symbol.className = '';
        
        // Check requirements
        if (password.length >= 8) 
        {
            length.className = 'valid';
        }
        
        if (/[A-Z]/.test(password)) 
        {
            uppercase.className = 'valid';
        }
        
        if (/[a-z]/.test(password)) 
        {
            lowercase.className = 'valid';
        }
        
        if (/[0-9]/.test(password)) 
        {
            number.className = 'valid';
        }
        
        if (/[!@#$%^&*]/.test(password)) 
        {
            symbol.className = 'valid';
        }
    }
    
    function isValidEmail(email) 
    {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidPassword(password) 
    {
        // Password must have at least 8 characters, one uppercase, one lowercase, one number, and one special character
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;
        return passwordRegex.test(password);
    }
    
    function showError(elementId, message) 
    {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
        }
    }
    
    function clearErrors() 
    {
        const errorElements = document.querySelectorAll('.error-text');
        errorElements.forEach(element => 
        {
            element.textContent = '';
        });
        
        document.getElementById('error-message').textContent = '';
        document.getElementById('error-message').className = 'error-message';
    }
});

function registerUser(name, surname, email, password, userType) 
{
    // Show loading state
    const registerBtn = document.getElementById('register-btn');
    const errorMessage = document.getElementById('error-message');
    registerBtn.disabled = true;
    registerBtn.textContent = 'Registering...';
    
    // Prepare data for API
    const formData = new FormData();
    formData.append('type', 'Register'); // The operation type is "Register"
    formData.append('name', name);
    formData.append('surname', surname);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('user_type', userType); // This is for the user type (Customer, Courier, etc.)
    
    // For debugging
    console.log('Sending registration data:', {
        type: 'Register',
        name: name,
        surname: surname,
        email: email,
        user_type: userType
    });
    
    // Create and send POST request using fetch
    fetch('../api.php', 
    {
        method: 'POST',
        body: formData
    })
    .then(response => 
    {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => 
    {
        console.log('API response:', data);
        
        if (data.status === 'success') 
        {
            // Registration successful
            errorMessage.classList.add('success-message');
            errorMessage.textContent = 'Registration successful! Redirecting to login...';
            
            // Save the API key if needed
            if (data.data && data.data.apikey) {
                localStorage.setItem('apiKey', data.data.apikey);
            }
            
            // Redirect to login page after 2 seconds
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 2000);
        } 
        else if (data.status === 409 || data.status === 'error') {
            // Email already exists or other error
            errorMessage.textContent = data.message || 'Registration failed. Please try again.';
            registerBtn.disabled = false;
            registerBtn.textContent = 'Register';
        } 
        else {
            // Other errors
            errorMessage.textContent = data.message || 'Registration failed. Please try again.';
            registerBtn.disabled = false;
            registerBtn.textContent = 'Register';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.textContent = 'Email already exists!';
        registerBtn.disabled = false;
        registerBtn.textContent = 'Register';
    });
}