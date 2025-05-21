<?php
header("Content-Type: application/json");
require_once 'config.php';
session_start();

$type = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['type'] ?? json_decode(file_get_contents("php://input"), true)['type'] ?? null)
    : ($_GET['type'] ?? null);

if (!$type) {
    echo json_encode(["status" => "error", "data" => "Request type missing"]);
    exit;
}

switch ($type) {
    case 'Register':
        handleRegister($pdo);
        break;

    case 'Login':
       handleLogin($pdo);
        break;

    case 'getAllProducts':
       handleGetAllProducts($pdo);
        break;

    default:
        echo json_encode(["status" => "error", "data" => "Unknown request type"]);
}


function handleRegister($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $name = $input['name'] ?? '';
    $surname = $input['surname'] ?? '';
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (!$name || !$surname || !$email || !$password) {
        echo json_encode(["status" => "error", "data" => "All fields are required."]);
        return;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (password, email, registrationDate, name, surname, phoneNo, country, city, street)
                               VALUES (?, ?, Now(), ?, ?, '', '', '', '')");
        $stmt->execute([$name, $surname, $email, $hashed]);
        echo json_encode(["status" => "success", "data" => "User registered successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Error: " . $e->getMessage()]);
    }
}

function handleLogin($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (!$email || !$password) {
        echo json_encode(["status" => "error", "data" => "Email and password required"]);
        return;
    }

    $stmt = $pdo->prepare("SELECT userID, name, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['userID'],
            'name' => $user['name'],
            'email' => $user['email']
        ];
        echo json_encode(["status" => "success", "data" => "Login successful"]);
    } else {
        echo json_encode(["status" => "error", "data" => "Invalid credentials"]);
    }
}

function handleGetAllProducts($pdo) {
    $stmt = $pdo->query("SELECT s.shoeID, s.name, s.price, s.image_url, b.name AS brand, c.type AS category
                         FROM shoes s
                         LEFT JOIN brands b ON s.brandID = b.brandID
                         LEFT JOIN categories c ON s.categoryID = c.categoryID");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $products]);
}
?>
