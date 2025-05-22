<?php
include 'config.php';
// At the very top of the file, before any output:
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
// Enhanced debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Request received: ' . print_r($_POST, true) . "\n", FILE_APPEND);

