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

    case 'GetRatedProducts':
       handleGetRatedProducts($pdo);
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

function isValidApiKey($pdo, $apikey) {
    $stmt = $pdo->prepare("SELECT userID FROM users WHERE apiKey = ?");
    $stmt->execute([$apikey]);
    return $stmt->fetchColumn() !== false;
}

function getUserIdFromApiKey($pdo, $apikey) {
    $stmt = $pdo->prepare("SELECT userID FROM users WHERE apiKey = ?");
    $stmt->execute([$apikey]);
    $userId = $stmt->fetchColumn();
    return $userId !== false ? $userId : null;
}

function requireValidApiKey($pdo) {
    $headers = getallheaders();
    $apikey = $headers['Authorization'] ?? '';

    if (!$apikey || !isValidApiKey($pdo, $apikey)) {
        echo json_encode(["status" => "error", "data" => "Invalid or missing API key"]);
        exit;
    }

    return $apikey;
}

function handleGetAllProducts($pdo) {
    $stmt = $pdo->query("SELECT s.shoeID, s.name, s.price, s.image_url, b.name AS brand, c.type AS category
                         FROM shoes s
                         LEFT JOIN brands b ON s.brandID = b.brandID
                         LEFT JOIN categories c ON s.categoryID = c.categoryID");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $products]);
}

function handleGetRatedProducts($pdo) {
    try {
        $query = "
            SELECT 
                s.shoeID,
                s.name,
                s.price,
                s.description,
                s.material,
                s.gender,
                s.image_url,
                s.size_range,
                s.colour,
                b.name AS brand_name,
                COUNT(r.reviewID) AS review_count,
                ROUND(AVG(r.rating), 1) AS avg_rating
            FROM shoes s
            INNER JOIN reviews_rating r ON s.shoeID = r.R_shoesID
            INNER JOIN brands b ON s.brandID = b.brandID
            GROUP BY s.shoeID
            ORDER BY review_count DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $ratedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(["status" => "success", "data" => $ratedProducts]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

?>
