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
        const phoneNo = document.getElementById('phoneNo').value;
        const country = document.getElementById('country').value;
        const city = document.getElementById('city').value;
        const street = document.getElementById('street').value;
        
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
            registerUser(name, surname, email, password, userType, phoneNo, country, city, street);
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

// Updated registerUser function with all parameters
function registerUser(name, surname, email, password, userType, phoneNo, country, city, street) {
    const registerBtn = document.getElementById('register-btn');
    const errorMessage = document.getElementById('error-message');
    registerBtn.disabled = true;
    registerBtn.textContent = 'Registering...';
    
    const requestData = {
        type: 'Register',
        name: name,
        surname: surname,
        email: email,
        password: password,
        user_type: userType,
        phoneNo: phoneNo || '',     // Provide empty string if not filled
        country: country || '',     // Provide empty string if not filled
        city: city || '',          // Provide empty string if not filled
        street: street || ''       // Provide empty string if not filled
    };
    
    fetch('../php/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        // First check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                throw new Error(text);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            errorMessage.classList.add('success-message');
            errorMessage.textContent = 'Registration successful! Redirecting to login...';
            
            if (data.data && data.data.apiKey) {
                localStorage.setItem('api_key', data.data.apiKey);
            }
            
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 2000);
        } else {
            errorMessage.textContent = data.data || 'Registration failed. Please try again.';
            registerBtn.disabled = false;
            registerBtn.textContent = 'Register';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.textContent = error.message || 'Registration failed. Please try again.';
        registerBtn.disabled = false;
        registerBtn.textContent = 'Register';
    });
}