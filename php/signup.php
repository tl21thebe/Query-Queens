<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/signup.css">
</head>
<body>
<div class="signup-container">
    <h2>Create an Account</h2>
    <div id="error-message" class="error-message"></div>
    <form id="signup-form" class="signup-form">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <span class="error-text" id="name-error"></span>
        </div>
        <div class="form-group">
            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" required>
            <span class="error-text" id="surname-error"></span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <span class="error-text" id="email-error"></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="error-text" id="password-error"></span>
            <div class="password-requirements">
                <p>Password must contain:</p>
                <ul>
                    <li id="length">At least 8 characters</li>
                    <li id="uppercase">At least one uppercase letter</li>
                    <li id="lowercase">At least one lowercase letter</li>
                    <li id="number">At least one number</li>
                    <li id="symbol">At least one special character (e.g., !@#$%^&*)</li>
                </ul>
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span class="error-text" id="confirm-password-error"></span>
        </div>
        <div class="form-group">
            <label for="type">User Type:</label>
            <select id="type" name="type" required>
                <option value="Customer">Customer</option>
                <option value="Admin">Admin</option>
            </select>
        </div>
        <div class="form-group">
            <div class="button-wrapper">
                <button type="submit" id="register-btn">Register</button>
            </div>
        </div>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<script src="../js/signup.js"></script>
</body>
</html>
