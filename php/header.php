<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}
// include_once 'config.php';

if (!function_exists('isLoggedIn')) 
{
    function isLoggedIn() 
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }
}

// Get user name for display
$user_name = '';
$user_type = '';

if (isLoggedIn()) {
    if (isset($_SESSION['user']['name'])) {
        $user_name = $_SESSION['user']['name'];
    } elseif (isset($_SESSION['user']['email'])) {
        $user_name = $_SESSION['user']['email'];
    }

    if (isset($_SESSION['user']['user_type'])) {
        $user_type = $_SESSION['user']['user_type'];
    }
}

// Get the base URL for the site
$base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$base_url .= $_SERVER['HTTP_HOST'];
$base_url .= dirname($_SERVER['PHP_SELF']);
if (substr($base_url, -1) !== '/') 
{
    $base_url .= '/';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare It</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>../css/style.css">
</head>
<header>
    <nav class = "navbar">
        <div class="nav-links">
            <a href="<?php echo $base_url; ?>products.php">COMPARE IT</a>
            <a href="<?php echo $base_url; ?>dashboard.php">DASHBOARD</a>
            <a href="<?php echo $base_url; ?>ratedProducts.php">TOP RATED⭐</a>

            <?php if ($user_type === 'Admin'): ?>
                <a href="<?php echo $base_url; ?>adminProduct.php">MANAGE PRODUCTS</a>
                <a href="<?php echo $base_url; ?>adminCategory.php">MANAGE CATEGORIES</a>
                <a href="<?php echo $base_url; ?>adminBrands.php">MANAGE BRANDS</a>
            <?php endif; ?>

            <?php if (isLoggedIn()): ?>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($user_name); ?></span>
                    <a href="<?php echo $base_url; ?>logout.php" class="logout-btn">LOGOUT</a>
                </div>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>login.php">LOGIN</a>
                <a href="<?php echo $base_url; ?>signup.php">REGISTER</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
