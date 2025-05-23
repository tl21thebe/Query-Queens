<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once 'config.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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

    case 'addProduct':
        handleaddProduct($pdo);
        break;

    case 'editProduct':
        handleeditProduct($pdo);
        break;

    case 'deleteProduct':
        handledeleteProduct($pdo);
        break;

case 'getBrands':
         handleGetBrands($pdo);
        break;

    case 'addBrand':
        handleAddBrand($pdo);
        break;

    case 'updateBrand':
        handleUpdateBrand($pdo);
        break;

    case 'deleteBrand':
        handleDeleteBrand($pdo);
        break;

 case 'getCategories':
    handleGetCategories($pdo);
    break;

case 'addCategory':
    handleAddCategory($pdo);
    break;

case 'updateCategory':
    handleUpdateCategory($pdo);
    break;

case 'deleteCategory':
    handleDeleteCategory($pdo);
    break;

case 'getStores':
    handleGetStores($pdo);
    break;

case 'addStore':
    handleAddStore($pdo);
    break;

case 'updateStore':
    handleUpdateStore($pdo);
    break;

case 'deleteStore':
    handleDeleteStore($pdo);
    break;

case 'productDetails':
    handleproductDetails($pdo);
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

    
    $apiKey = bin2hex(random_bytes(16));
    
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    try {
        
        $stmt = $pdo->prepare("INSERT INTO users (
                                password, 
                                email, 
                                registrationDate, 
                                name, 
                                surname, 
                                phoneNo, 
                                country, 
                                city, 
                                street,
                                apiKey) 
                              VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $hashed,              
            $email,               
            $name,                
            $surname,             
            '000-000-0000',       
            'South Africa',       
            'Pretoria',           
            'Default Street',     
            $apiKey               
        ]);
        
        echo json_encode([
            "status" => "success", 
            "data" => [
                "message" => "User registered successfully",
                "userId" => $pdo->lastInsertId(),
                "apiKey" => $apiKey
            ]
        ]);
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

    try {
        $stmt = $pdo->prepare("SELECT userID, name, email, password, apiKey FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION['user'] = [
                'id' => $user['userID'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            
            
            $apiKey = $user['apiKey'];
            if (!$apiKey) {
                $apiKey = bin2hex(random_bytes(16));
                $stmt = $pdo->prepare("UPDATE users SET apiKey = ? WHERE userID = ?");
                $stmt->execute([$apiKey, $user['userID']]);
            }
            
            echo json_encode([
                "status" => "success", 
                "data" => [
                    "message" => "Login successful",
                    "userId" => $user['userID'],
                    "name" => $user['name'],
                    "apiKey" => $apiKey
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "data" => "Invalid credentials"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

function isValidApiKey($pdo, $apikey) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE apiKey = ?");
        $stmt->execute([$apikey]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

function getUserIdFromApiKey($pdo, $apikey) {
    try {
        $stmt = $pdo->prepare("SELECT userID FROM users WHERE apiKey = ?");
        $stmt->execute([$apikey]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return null;
    }
}

function requireValidApiKey($pdo) {
    $headers = getallheaders();
    $apikey = $headers['Authorization'] ?? '';
    
    
    if (strpos($apikey, 'Bearer ') === 0) {
        $apikey = substr($apikey, 7);
    }

    if (!$apikey || !isValidApiKey($pdo, $apikey)) {
        echo json_encode(["status" => "error", "data" => "Invalid or missing API key"]);
        exit;
    }

    return $apikey;
}

function handleGetAllProducts($pdo) {
    try {
        $stmt = $pdo->query("SELECT s.shoeID, s.name, s.price, s.image_url, 
                              b.name AS brand, c.type AS category
                              FROM shoes s
                              LEFT JOIN brands b ON s.brandID = b.brandID
                              LEFT JOIN categories c ON s.categoryID = c.categoryID
                              ORDER BY s.name");

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $products]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
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
                COUNT(r.reviewID) AS review_count
            FROM shoes s
            LEFT JOIN reviews_rating r ON s.shoeID = r.R_shoesID
            LEFT JOIN brands b ON s.brandID = b.brandID
            GROUP BY s.shoeID
            ORDER BY review_count DESC
            LIMIT 10
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $ratedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(["status" => "success", "data" => $ratedProducts]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

function handleaddProduct($pdo){
    $input = json_decode(file_get_contents("php://input"), true);

    $name = $input['name'] ?? '';
    $price = $input['price'] ?? 0;
    $brandID = $input['brandID'] ?? null;
    $categoryID = $input['categoryID'] ?? null;
    $image_url = $input['image_url'] ?? '';
    $description = $input['description'] ?? '';

    if (!$name || !$brandID || !$categoryID) {
        echo json_encode(["status" => "error", "data" => "Missing required fields"]);
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO shoes (name, price, brandID, categoryID, image_url, description, releaseDate, material, gender, colour)
                           VALUES (?, ?, ?, ?, ?, ?, CURDATE(), '', 'Prefer not to say', 'Black')");
    $stmt->execute([$name, $price, $brandID, $categoryID, $image_url, $description]);

    echo json_encode(["status" => "success", "data" => "Product added successfully"]);
}

function handleeditProduct($pdo){
    $input = json_decode(file_get_contents("php://input"), true);
    $shoeID = $input['shoeID'] ?? null;

    if (!$shoeID) {
        echo json_encode(["status" => "error", "data" => "Missing shoe ID"]);
        return;
    }

    $name = $input['name'] ?? '';
    $price = $input['price'] ?? 0;
    $description = $input['description'] ?? '';
    $image_url = $input['image_url'] ?? '';

    $stmt = $pdo->prepare("UPDATE shoes SET name = ?, price = ?, description = ?, image_url = ? WHERE shoeID = ?");
    $stmt->execute([$name, $price, $description, $image_url, $shoeID]);

    echo json_encode(["status" => "success", "data" => "Product updated successfully"]);
}

function handledeleteProduct($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $shoeID = $input['shoeID'] ?? null;

    if (!$shoeID) {
        echo json_encode(["status" => "error", "data" => "Missing shoe ID"]);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM shoes WHERE shoeID = ?");
    $stmt->execute([$shoeID]);

    echo json_encode(["status" => "success", "data" => "Product deleted successfully"]);

}

function handleGetBrands($pdo) {
    try {
        $stmt = $pdo->query("SELECT brandID, name FROM brands ORDER BY name");
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["status" => "success", "data" => $brands]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

function handleAddBrand($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $name = $input['name'] ?? '';

    if (!$name) {
        echo json_encode(["status" => "error", "data" => "Brand name is required"]);
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO brands (name) VALUES (?)");
        $stmt->execute([$name]);
        echo json_encode(["status" => "success", "data" => ["message" => "Brand added", "brandID" => $pdo->lastInsertId()]]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

function handleUpdateBrand($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $brandID = $input['brandID'] ?? null;
    $name = $input['name'] ?? '';

    if (!$brandID || !$name) {
        echo json_encode(["status" => "error", "data" => "Brand ID and new name required"]);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE brands SET name = ? WHERE brandID = ?");
        $stmt->execute([$name, $brandID]);
        echo json_encode(["status" => "success", "data" => "Brand updated"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

function handleDeleteBrand($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $brandID = $input['brandID'] ?? null;

    if (!$brandID) {
        echo json_encode(["status" => "error", "data" => "Brand ID required"]);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM brands WHERE brandID = ?");
        $stmt->execute([$brandID]);
        echo json_encode(["status" => "success", "data" => "Brand deleted"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

//changed this function because I changed the categories attribute name from type to catType
function handleGetCategories($pdo) {    
    $stmt = $pdo->query("SELECT categoryID, catType FROM categories ORDER BY catType");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $categories]);
}

function handleAddCategory($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $catType = trim($input['catType'] ?? '');
    if (!$catType) {
        echo json_encode(["status" => "error", "data" => "Category name required"]);
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO categories (catType) VALUES (?)");
    $stmt->execute([$catType]);
    echo json_encode(["status" => "success", "data" => "Category added"]);
}

function handleUpdateCategory($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $categoryID = $input['categoryID'] ?? null;
    $catType = trim($input['catType'] ?? '');

    if (!$categoryID || !$catType) {
        echo json_encode(["status" => "error", "data" => "ID and name required"]);
        return;
    }

    $stmt = $pdo->prepare("UPDATE categories SET catType = ? WHERE categoryID = ?");
    $stmt->execute([$catType, $categoryID]);
    echo json_encode(["status" => "success", "data" => "Category updated"]);
}

function handleDeleteCategory($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $categoryID = $input['categoryID'] ?? null;

    if (!$categoryID) {
        echo json_encode(["status" => "error", "data" => "ID required"]);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM categories WHERE categoryID = ?");
    $stmt->execute([$categoryID]);
    echo json_encode(["status" => "success", "data" => "Category deleted"]);
}

function handleGetStores($pdo) {
    $stmt = $pdo->query("SELECT storeID, name FROM stores ORDER BY name");
    $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $stores]);
}

function handleAddStore($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    if (!$name || !$email) {
        echo json_encode(["status" => "error", "data" => "Store name and email required"]);
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO stores (name,email) VALUES (?,?)");
    $stmt->execute([$name,$email]);
    echo json_encode(["status" => "success", "data" => "Store added"]);
}

function handleUpdateStore($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $storeID = $input['storeID'] ?? null;
    $name = trim($input['name'] ?? '');

    if (!$storeID || !$name) {
        echo json_encode(["status" => "error", "data" => "ID and name required"]);
        return;
    }

    $stmt = $pdo->prepare("UPDATE stores SET name = ? WHERE storeID = ?");
    $stmt->execute([$name, $storeID]);
    echo json_encode(["status" => "success", "data" => "Store updated"]);
}

function handleDeleteStore($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $storeID = $input['storeID'] ?? null;

    if (!$storeID) {
        echo json_encode(["status" => "error", "data" => "ID required"]);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM stores WHERE storeID = ?");
    $stmt->execute([$storeID]);
    echo json_encode(["status" => "success", "data" => "Store deleted"]);
}

function handleproductDetails($pdo){
    $stmt = $pdo->prepare("
        SELECT s.shoeID, s.name, s.price, s.image_url, s.description, s.colour, s.gender, 
               s.releaseDate, s.material, b.name AS brand, c.catType AS category
        FROM shoes s
        LEFT JOIN brands b ON s.brandID = b.brandID
        LEFT JOIN categories c ON s.categoryID = c.categoryID
        WHERE s.shoeID = ?
    ");

    
    $input = json_decode(file_get_contents("php://input"), true);///extract the shoeID from a POST body
    $shoeID = $input['shoeID'] ?? ($_GET['shoeID'] ?? null);

    $stmt->execute([$shoeID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$product) {
        echo json_encode(["status" => "error", "data" => "Product not found"]);
        return;
    }

    /////i think i need to upload rows in the 'has' table 
    $storeStmt = $pdo->prepare("
        SELECT st.name AS store_name, st.email, h.storeID
        FROM stores st
        JOIN has h ON h.storeID = st.storeID
        WHERE h.shoeID = ?
    ");
    $storeStmt->execute([$shoeID]);
    $product['stores'] = $storeStmt->fetchAll(PDO::FETCH_ASSOC);

    $reviewStmt = $pdo->prepare("
        SELECT r.description, u.name AS reviewer
        FROM reviews_rating r
        JOIN users u ON u.userID = r.R_userID
        WHERE r.R_shoesID = ?
    ");
    $reviewStmt->execute([$shoeID]);
    $product['reviews'] = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $product
    ]);
}



?>
