<?php
include 'header.php'; 
include 'footer.php';
?>

<!-- Add the specific CSS for this page -->
<link rel="stylesheet" href="signup.css">

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
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span class="error-text" id="confirm-password-error"></span>
        </div>
        <div class="form-group">
            <button type="submit" id="register-btn">Register</button>
        </div>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>
