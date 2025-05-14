<?php
include 'header.php';
include 'footer.php';
?>

<link rel = "stylesheet" href = "login.css">

<main>
    <h2>Login</h2>
    <?php if(isset($_SESSION['user'])): ?>
         <p>You are already logged in. You can now:</p>
    <?php else: ?>
        <form method="post" id="loginForm">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Login</button>
            <div id="loginMessage"></div>
        </form>
    <?php endif; ?>
</main>