<?php
session_start();

// Clear all session data
$_SESSION = array();

// Destroy the session
session_destroy();

// Clear client-side storage and redirect
echo "<script>
    sessionStorage.removeItem('api_key');
    sessionStorage.removeItem('user');
    window.location.href = 'login.php';
</script>";
exit;
?>