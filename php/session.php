<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

// === DEBUGGING LOG SETUP === //
$debugFile = __DIR__ . "/debug.log";
function log_debug($message) 
{
    global $debugFile;
    file_put_contents($debugFile, "[" . date("Y-m-d H:i:s") . "] " . $message . "\n", FILE_APPEND);
}

$rawInput = file_get_contents("php://input");
log_debug("RAW INPUT: " . $rawInput);

// Accept both form-data and JSON input
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : "";
$requestData = [];

if (strpos($contentType, 'application/json') !== false) 
{
    log_debug("Detected JSON request");
    $requestData = json_decode($rawInput, true);
    if ($requestData === null) 
    {
        log_debug("JSON DECODE ERROR");
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Malformed JSON']);
        exit;
    }
}
else 
{
    log_debug("Detected form-data request");
    $requestData = $_POST;
}

log_debug("REQUEST DATA: " . print_r($requestData, true));

// Handle different session actions
$type = isset($requestData['type']) ? $requestData['type'] : '';
log_debug("TYPE: " . $type);

switch ($type) 
{
    case 'Login':
        log_debug("Handling login");
        handleLogin($requestData);
        break;
    case 'logout':
        log_debug("Handling logout");
        handleLogout();
        break;
    case 'check':
        log_debug("Handling session check");
        checkSession();
        break;
    default:
        log_debug("Invalid type or missing");
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid type']);
        break;
}

function handleLogin($data) 
{
    global $pdo;

    $email = isset($data['email']) ? $data['email'] : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (empty($email) || empty($password)) 
    {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        return;
    }

    try 
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) 
        {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
            return;
        }

        list($salt, $hash) = explode(':', $user['password']);
        $passwordHash = hash('sha256', $salt . $password);

        if ($passwordHash !== $hash) 
        {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
            return;
        }

        $_SESSION['user'] = 
        [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'api_key' => $user['api_key']
        ];

        echo json_encode([
            'status' => 'success',
            'timestamp' => time() * 1000,
            'data' => [
                'api_key' => $user['api_key'],
                'email' => $user['email'],
                'name' => $user['name']
            ]
        ]);

    } 
    catch (PDOException $e) 
    {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
}

