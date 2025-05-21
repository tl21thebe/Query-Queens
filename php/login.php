<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CompareIt</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="form-container">
            <div class="form-btn">
                <h2>Login</h2>
            </div>
            
            <form id="login-form">
                <div class="form-group">
                    <input type="email" id="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Login</button>
                <p id="error-msg" class="error-message"></p>
                <p>New to CompareIt? <a href="signup.php">Signup here!</a></p>
            </form>
        </div>
    </div>
</div>

</body>
</html>
