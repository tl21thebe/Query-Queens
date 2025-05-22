<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}


$connectionOptions = [
    
    [
        'host' => 'localhost',
        'dbname' => 'u23591481_queryqueens_compareIt',
        'user' => 'u23591481',
        'pass' => 'YFOQ5R4KUUEFVGAZR2FQDGWRJT6LE5Z3'
    ],
    
    [
        'host' => 'wheatley.cs.up.ac.za',
        'dbname' => 'u23591481_queryqueens_compareIt',
        'user' => 'u23591481',
        'pass' => 'YFOQ5R4KUUEFVGAZR2FQDGWRJT6LE5Z3'
    ],
    
    [
        'host' => '127.0.0.1',
        'dbname' => 'u23591481_queryqueens_compareIt',
        'user' => 'u23591481',
        'pass' => 'YFOQ5R4KUUEFVGAZR2FQDGWRJT6LE5Z3'
    ]
];

$pdo = null;
$lastError = null;


foreach ($connectionOptions as $option) {
    try {
        $dsn = "mysql:host={$option['host']};dbname={$option['dbname']};charset=utf8";
        $pdo = new PDO($dsn, $option['user'], $option['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        error_log("Successfully connected to database on {$option['host']}");
        break;
    } catch (PDOException $e) {
        $lastError = $e;
        error_log("Connection failed for {$option['host']}: " . $e->getMessage());
        
    }
}


if (!$pdo) {
    error_log("All database connection attempts failed");
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
        die(json_encode([
            "status" => "error",
            "timestamp" => time() * 1000,
            "data" => "Database connection failed: " . $lastError->getMessage()
        ]));
    }
}
?>
