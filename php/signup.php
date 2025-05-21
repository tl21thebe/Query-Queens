<div class="container">
    <div class="form-wrapper">
        <h2>Sign Up</h2>
        <form id="signup-form" method="POST">
            <div class="form-group">
                <label for="name">First Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name">
            </div>

            <div class="form-group">
                <label for="surname">Last Name</label>
                <input type="text" name="surname" id="surname" placeholder="Enter your surname">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <small class="password-hint">
                    🔒 Your password must contain:
                    <ul style="margin-top: 5px; padding-left: 20px;">
                        <li>🔠 At least one uppercase letter</li>
                        <li>🔡 At least one lowercase letter</li>
                        <li>🔢 At least one number</li>
                        <li>🔣 At least one special character (e.g., !@#$%)</li>
                        <li>📏 Minimum 8 characters long</li>
                    </ul>
                </small>
            </div>


            <div class="form-group">
                <label for="type">User Type</label>
                <select name="type" id="type">
                    <option value="">-- Select Type --</option>
                    <option value="customer">Customer</option>
                    <option value="courier">Administrator</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>

            <p id="error-msg" class="error-message"></p>
        </form>
    </div>
</div>
<script src="js/signupValidation.js"></script>
<?php include('footer.php'); ?>
