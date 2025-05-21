<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}
// include_once 'config.php';

if (!function_exists('isLoggedIn')) 
{
    function isLoggedIn() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }
}

// Get user name for display
$user_name = '';
if (isLoggedIn() && isset($_SESSION['user']['name'])) 
{
    $user_name = $_SESSION['user']['name'];
} 
elseif (isLoggedIn() && isset($_SESSION['user']['email'])) 
{
    $user_name = $_SESSION['user']['email'];
}

// Get the base URL for the site
$base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$base_url .= $_SERVER['HTTP_HOST'];
$base_url .= dirname($_SERVER['PHP_SELF']);
if (substr($base_url, -1) !== '/') 
{
    $base_url .= '/';
}

// Determine current theme from cookie or default to light
$current_theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
$theme_class = $current_theme === 'dark' ? 'dark-theme' : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare It</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        // Early theme initialization
        (function() 
        {
            let savedTheme = localStorage.getItem('theme');
            if (!savedTheme) 
            {
                const cookieMatch = document.cookie.match(/theme=([^;]+)/);
                if (cookieMatch) 
                {
                    savedTheme = cookieMatch[1];
                }
            }
            if (savedTheme === 'dark') 
            {
                document.documentElement.classList.add('dark-theme');
                document.body.classList.add('dark-theme');
            }
        })();
    </script>
</head>
<body class = "<?php echo $theme_class; ?>">
<header>
    <nav>
        <div class="theme-toggle-container">
            <button id="theme-toggle">
                <?php echo ($current_theme === 'dark') ? 'Light Mode' : 'Dark Mode'; ?>
            </button>
        </div>

        <div class="navigation-links">
            <a href="<?php echo $base_url; ?>CompareIt.php">CompareIt.php</a>
            <a href="<?php echo $base_url; ?>shopping.php">SHOPPING</a>
            <a href="<?php echo $base_url; ?>deals.php">DEALS</a>
            <a href="<?php echo $base_url; ?>login.php">LOGIN</a>
            <a href="<?php echo $base_url; ?>signup.php">SIGN IN</a>
            <?php if (isLoggedIn()): ?>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($user_name); ?></span>
                    <a href="<?php echo $base_url; ?>logout.php" class="logout-btn">Logout</a>
                </div>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>login.php">Login</a>
                <a href="<?php echo $base_url; ?>signup.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<!-- Load theme script after DOM is ready -->
<script src="../js/theme.js"></script>

<main>
