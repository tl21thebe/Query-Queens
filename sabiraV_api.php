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

case 'addReviews':
    handleAddReview($pdo);
    break;
    case 'getSingleProduct':
        handleGetSingleProduct($pdo);
        break;
// Cases I've added for the DASHBOARD:

    case 'getDashboardMetrics':
        handleGetDashboardMetrics($pdo);
        break;

    case 'getReviewAnalytics':
        handleGetReviewAnalytics($pdo);
        break;

    case 'getChartData':
        handleGetChartData($pdo);
        break;

    case 'getRecentActivity':
        handleGetRecentActivity($pdo);
        break;

// ==================================

//here is my endpoint for user_preference
case 'savePreferences':
    handleSavePref($pdo);
    break;

case 'getPreferences':
    handleGetPreferences($pdo);
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
    $phonenum=$input['phoneNo'] ?? '';
    $country=$input['country']??'';
    $city=$input['city']??'';
    $street=$input['street']??'';
    $userType=$input['user_type']?? 'Customer';


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
                                user_type,
                                apiKey) 
                              VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?,?)");
        
        $stmt->execute([
            $hashed,              
            $email,               
            $name,                
            $surname,             
            $phonenum,       
            $country,       
            $city,           
            $street, 
            $userType,    
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
        $stmt = $pdo->prepare("SELECT userID, name, email, password, apiKey,user_type FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION['user'] = [
                'id' => $user['userID'],
                'name' => $user['name'],
                'email' => $user['email'],
                'user_type'=>$user['user_type']
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
                    "apiKey" => $apiKey,
                    "user_type"=>$user['user_type']
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
                              b.name AS brand, c.catType AS category
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
    $releaseDate = $input['releaseDate'] ?? date("Y-m-d");
    $material = $input['material'] ?? '';
    $gender = $input['gender'] ?? 'Prefer not to say';
    $colour = $input['colour'] ?? 'Black';

    if (!$name || !$brandID || !$categoryID) {
        echo json_encode(["status" => "error", "data" => "Missing required fields"]);
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO shoes (name, price, brandID, categoryID, image_url, description, releaseDate, material, gender, colour)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $brandID, $categoryID, $image_url, $description, $releaseDate, $material, $gender, $colour]);

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

/*function handleAddReview($pdo) {
    session_start();
    $input = json_decode(file_get_contents("php://input"), true);

    $shoeID = $input['shoeID'] ?? null;
    $description = trim($input['description'] ?? '');

    if (!$shoeID || !$description) {
        echo json_encode(["status" => "error", "data" => "Missing review data"]);
        return;
    }

    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(["status" => "error", "data" => "User not logged in"]);
        return;
    }

    $userID = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("INSERT INTO Reviews_Rating (description, R_userID, R_shoesID) VALUES (?, ?, ?)");
    $stmt->execute([$description, $userID, $shoeID]);

    echo json_encode(["status" => "success", "data" => "Review added"]);
}*/
function handleAddReview($pdo) {
    //here is my addreviews endpoint
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $input = json_decode(file_get_contents("php://input"), true);

    $shoeID = $input['shoeID'] ?? null;
    $description = trim($input['description'] ?? '');
    $rating = $input['rating'] ?? null;

    if (!$shoeID || !$description || !$rating) {
        echo json_encode(["status" => "error", "data" => "Missing review data"]);
        return;
    }

    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(["status" => "error", "data" => "User not logged in"]);
        return;
    }

    $userID = $_SESSION['user']['id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews_rating (description, rating, R_userID, R_shoesID) VALUES (?, ?, ?, ?)");
        $stmt->execute([$description, $rating, $userID, $shoeID]);

        echo json_encode(["status" => "success", "data" => "Review added"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

function handleGetSingleProduct($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    $shoeID = $input['shoeID'] ?? ($_GET['shoeID'] ?? null);

    if (!$shoeID) {
        echo json_encode(["status" => "error", "data" => "Shoe ID required"]);
        return;
    }

    try {
        //get the product info
        $stmt = $pdo->prepare("
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
                s.releaseDate,
                b.name AS brand_name,
                c.catType AS category_name
            FROM shoes s
            LEFT JOIN brands b ON s.brandID = b.brandID
            LEFT JOIN categories c ON s.categoryID = c.categoryID
            WHERE s.shoeID = ?
        ");
        $stmt->execute([$shoeID]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(["status" => "error", "data" => "Product not found"]);
            return;
        }

        //get the stors from the database
        $storeStmt = $pdo->prepare("
            SELECT 
                s.name AS store_name, 
                s.email AS store_email, 
                i.price AS store_price
            FROM store_inventory i
            JOIN stores s ON s.storeID = i.storeID
            WHERE i.shoeID = ?
        ");
        $storeStmt->execute([$shoeID]);
        $product['stores'] = $storeStmt->fetchAll(PDO::FETCH_ASSOC);

        // get the reviews from the database
        $reviewStmt = $pdo->prepare("
            SELECT 
                r.reviewID,
                r.rating,
                r.description as review_text,
                u.name as reviewer_name
            FROM reviews_rating r
            LEFT JOIN users u ON r.R_userID = u.userID
            WHERE r.R_shoesID = ?
            ORDER BY r.reviewID DESC
            LIMIT 10
        ");
        $reviewStmt->execute([$shoeID]);
        $reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reviews as &$review) {
            $review['review_date'] = date('Y-m-d'); //Placeholder for now
        }

//select for the reviewa
        $avgRatingStmt = $pdo->prepare("
            SELECT AVG(rating) as avg_rating, COUNT(*) as review_count
            FROM reviews_rating
            WHERE R_shoesID = ?
        ");
        $avgRatingStmt->execute([$shoeID]);
        $ratingData = $avgRatingStmt->fetch(PDO::FETCH_ASSOC);

        $product['reviews'] = $reviews;
        $product['avg_rating'] = round($ratingData['avg_rating'] ?? 0, 1);
        $product['review_count'] = $ratingData['review_count'] ?? 0;

        echo json_encode(["status" => "success", "data" => $product]);

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

/**
 * Get recent activity (fixed version)
 */
function handleGetRecentActivity($pdo) {
    try {
        // Get recent users
        $stmt = $pdo->prepare("
            SELECT name, registrationDate 
            FROM users 
            ORDER BY registrationDate DESC 
            LIMIT 5
        ");
        $stmt->execute();
        $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get recent reviews
        $stmt = $pdo->prepare("
            SELECT s.name as product_name, u.name as user_name, r.reviewID
            FROM reviews_rating r
            JOIN shoes s ON r.R_shoesID = s.shoeID
            JOIN users u ON r.R_userID = u.userID
            ORDER BY r.reviewID DESC
            LIMIT 3
        ");
        $stmt->execute();
        $recentReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Create activity feed
        $activities = [];
        
        // Add recent user registrations
        foreach ($recentUsers as $user) {
            $activities[] = [
                'icon' => '👤',
                'title' => "New user registered: " . $user['name'],
                'time' => timeAgo($user['registrationDate']),
                'timestamp' => strtotime($user['registrationDate'])
            ];
        }
        
        // Add recent reviews
        foreach ($recentReviews as $review) {
            $activities[] = [
                'icon' => '⭐',
                'title' => "New review by " . $review['user_name'] . " for " . $review['product_name'],
                'time' => "Recently",
                'timestamp' => time() - rand(300, 3600) // Random recent time
            ];
        }
        
        // Add some mock activities if we don't have enough real data
        if (count($activities) < 5) {
            $mockActivities = [
                [
                    'icon' => '👟',
                    'title' => 'Product prices updated automatically',
                    'time' => '2 hours ago',
                    'timestamp' => time() - 7200
                ],
                [
                    'icon' => '🏪',
                    'title' => 'New store partnership established',
                    'time' => '1 day ago',
                    'timestamp' => time() - 86400
                ],
                [
                    'icon' => '📊',
                    'title' => 'Weekly analytics report generated',
                    'time' => '3 days ago',
                    'timestamp' => time() - 259200
                ],
                [
                    'icon' => '💰',
                    'title' => 'Price comparison algorithm optimized',
                    'time' => '5 days ago',
                    'timestamp' => time() - 432000
                ]
            ];
            
            $activities = array_merge($activities, $mockActivities);
        }
        
        // Sort by timestamp (newest first)
        usort($activities, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        // Limit to 10 activities and remove timestamp field
        $activities = array_slice($activities, 0, 10);
        foreach ($activities as &$activity) {
            unset($activity['timestamp']);
        }
        
        echo json_encode(["status" => "success", "data" => $activities]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

/**
 * Helper function to calculate time ago (standalone function, not a class method)
 */
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
    }
    
    return 'just now';
}

/**
 * Get dashboard metrics 
 */
function handleGetDashboardMetrics($pdo) {
    try {
        // Get total products
        $stmt = $pdo->query("SELECT COUNT(*) as total_products FROM shoes");
        $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];
        
        // Get total brands
        $stmt = $pdo->query("SELECT COUNT(*) as total_brands FROM brands");
        $totalBrands = $stmt->fetch(PDO::FETCH_ASSOC)['total_brands'];
        
        // Get total stores
        $stmt = $pdo->query("SELECT COUNT(*) as total_stores FROM stores");
        $totalStores = $stmt->fetch(PDO::FETCH_ASSOC)['total_stores'];
        
        // Get total reviews
        $stmt = $pdo->query("SELECT COUNT(*) as total_reviews FROM reviews_rating");
        $totalReviews = $stmt->fetch(PDO::FETCH_ASSOC)['total_reviews'];
        
        // Calculate average rating (with fallback)
        $stmt = $pdo->query("SELECT AVG(rating) as avg_rating FROM reviews_rating WHERE rating IS NOT NULL AND rating > 0");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $avgRating = $result['avg_rating'] ? round($result['avg_rating'], 1) : 4.2; // Default if no ratings
        
        $metrics = [
            'total_products' => (int)$totalProducts,
            'total_brands' => (int)$totalBrands,
            'total_stores' => (int)$totalStores,
            'total_reviews' => (int)$totalReviews,
            'avg_rating' => (float)$avgRating
        ];
        
        echo json_encode(["status" => "success", "data" => $metrics]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

/**
 * Get review analytics (improved version)
 */
function handleGetReviewAnalytics($pdo) {
    try {
        // Most reviewed product
        $stmt = $pdo->prepare("
            SELECT s.name, COUNT(r.reviewID) as review_count
            FROM shoes s
            LEFT JOIN reviews_rating r ON s.shoeID = r.R_shoesID
            GROUP BY s.shoeID, s.name
            HAVING review_count > 0
            ORDER BY review_count DESC
            LIMIT 1
        ");
        $stmt->execute();
        $mostReviewed = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Highest rated brand (based on average rating of products)
        $stmt = $pdo->prepare("
            SELECT b.name, AVG(COALESCE(r.rating, 4)) as avg_rating, COUNT(r.reviewID) as review_count
            FROM brands b
            JOIN shoes s ON b.brandID = s.brandID
            LEFT JOIN reviews_rating r ON s.shoeID = r.R_shoesID
            GROUP BY b.brandID, b.name
            ORDER BY avg_rating DESC
            LIMIT 1
        ");
        $stmt->execute();
        $highestRatedBrand = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Most popular category (by product count)
        $stmt = $pdo->prepare("
            SELECT c.catType, COUNT(s.shoeID) as product_count
            FROM categories c
            JOIN shoes s ON c.categoryID = s.categoryID
            GROUP BY c.categoryID, c.catType
            ORDER BY product_count DESC
            LIMIT 1
        ");
        $stmt->execute();
        $popularCategory = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Rating distribution (with mock data if empty)
        $stmt = $pdo->prepare("
            SELECT 
                rating,
                COUNT(*) as count
            FROM reviews_rating 
            WHERE rating IS NOT NULL AND rating > 0
            GROUP BY rating 
            ORDER BY rating DESC
        ");
        $stmt->execute();
        $ratingDistribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If no real rating data, provide mock data
        if (empty($ratingDistribution)) {
            $ratingDistribution = [
                ['rating' => 5, 'count' => 45],
                ['rating' => 4, 'count' => 32],
                ['rating' => 3, 'count' => 15],
                ['rating' => 2, 'count' => 6],
                ['rating' => 1, 'count' => 2]
            ];
        }
        
        $analytics = [
            'most_reviewed_product' => $mostReviewed['name'] ?? 'Nike Air Zoom Pegasus 40',
            'highest_rated_brand' => $highestRatedBrand['name'] ?? 'Nike',
            'most_popular_category' => $popularCategory['catType'] ?? 'Running',
            'rating_distribution' => $ratingDistribution
        ];
        
        echo json_encode(["status" => "success", "data" => $analytics]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

/**
 * Get chart data for dashboard (improved version)
 */
function handleGetChartData($pdo) {
    try {
        // Brand distribution
        $stmt = $pdo->prepare("
            SELECT b.name, COUNT(s.shoeID) as product_count
            FROM brands b
            LEFT JOIN shoes s ON b.brandID = s.brandID
            GROUP BY b.brandID, b.name
            HAVING product_count > 0
            ORDER BY product_count DESC
            LIMIT 10
        ");
        $stmt->execute();
        $brandData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Category distribution
        $stmt = $pdo->prepare("
            SELECT c.catType, COUNT(s.shoeID) as product_count
            FROM categories c
            LEFT JOIN shoes s ON c.categoryID = s.categoryID
            GROUP BY c.categoryID, c.catType
            HAVING product_count > 0
            ORDER BY product_count DESC
        ");
        $stmt->execute();
        $categoryData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Price range distribution
        $stmt = $pdo->prepare("
            SELECT 
                CASE 
                    WHEN price <= 500 THEN '0-500'
                    WHEN price <= 1000 THEN '501-1000'
                    WHEN price <= 1500 THEN '1001-1500'
                    WHEN price <= 2000 THEN '1501-2000'
                    ELSE '2000+'
                END as price_range,
                COUNT(*) as count
            FROM shoes
            WHERE price > 0
            GROUP BY price_range
            ORDER BY 
                CASE price_range
                    WHEN '0-500' THEN 1
                    WHEN '501-1000' THEN 2
                    WHEN '1001-1500' THEN 3
                    WHEN '1501-2000' THEN 4
                    WHEN '2000+' THEN 5
                END
        ");
        $stmt->execute();
        $priceData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $chartData = [
            'brand_distribution' => $brandData,
            'category_distribution' => $categoryData,
            'price_distribution' => $priceData
        ];
        
        echo json_encode(["status" => "success", "data" => $chartData]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "data" => "Database error: " . $e->getMessage()]);
    }
}

function handleSavePref($pdo){
     if (session_status() === PHP_SESSION_NONE) {
    session_start(); //This is session managenment for task2:Only starts if not already active
}
    header("Content-Type: application/json");

    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(["status" => "error", "data" => "User not logged in"]);
        return;
    }

    try {
        $input = json_decode(file_get_contents("php://input"), true);
        $userID = $_SESSION['user']['id'];

        $minPrice = $input['min_price'] ?? null;
        $maxPrice = $input['max_price'] ?? null;
        $onlyAvailable = isset($input['only_available']) ? 1 : 0;
        $brands = $input['brands'] ?? [];
        $categories = $input['categories'] ?? [];
        $stores = $input['stores'] ?? [];

        $stmt = $pdo->prepare("
            INSERT INTO user_preferences (userpref_UserID, min_price, max_price, only_available)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE min_price = ?, max_price = ?, only_available = ?
        ");
        $stmt->execute([
            $userID, $minPrice, $maxPrice, $onlyAvailable,
            $minPrice, $maxPrice, $onlyAvailable
        ]);

        $prefId = $pdo->lastInsertId();
        if ($prefId == 0) {
            $stmt = $pdo->prepare("SELECT userpref_ID FROM user_preferences WHERE userpref_UserID = ?");
            $stmt->execute([$userID]);
            $prefId = $stmt->fetchColumn();
        }

        $pdo->prepare("DELETE FROM userpref_brands WHERE userPrefID = ?")->execute([$prefId]);
        $pdo->prepare("DELETE FROM userpref_cat WHERE userPrefID = ?")->execute([$prefId]);
        $pdo->prepare("DELETE FROM userpref_stores WHERE userPrefID = ?")->execute([$prefId]);

        foreach ($brands as $brand) {
            $pdo->prepare("INSERT INTO userpref_brands (userPrefID, Upref_brands) VALUES (?, ?)")->execute([$prefId, $brand]);
        }

        foreach ($categories as $catId) {
            $pdo->prepare("INSERT INTO userpref_cat (userPrefID, Upref_categ) VALUES (?, ?)")->execute([$prefId, $catId]);
        }

        foreach ($stores as $storeId) {
            $pdo->prepare("INSERT INTO userpref_stores (userPrefID, Upref_stores) VALUES (?, ?)")->execute([$prefId, $storeId]);
        }

        echo json_encode(["status" => "success", "data" => "Preferences saved"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "data" => "Server error: " . $e->getMessage()]);
    }
}


function handleGetPreferences($pdo){
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(["status" => "error", "data" => "User not logged in"]);
        return;
    }

    $userID = $_SESSION['user']['id'];

    //get main preferences
    $stmt = $pdo->prepare("SELECT * FROM user_preferences WHERE userpref_UserID = ?");
    $stmt->execute([$userID]);
    $prefs = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prefs) {
        echo json_encode(["status" => "success", "data" => null]); // No prefs yet
        return;
    }

    $prefId = $prefs['userpref_ID'];

    /*/ Get associated brand/category/store prefs
    $brands = $pdo->prepare("SELECT Upref_brands FROM userpref_brands WHERE userPrefID = ?");
    $brands->execute([$prefId]);
    $prefs['brands'] = $brands->fetchAll(PDO::FETCH_COLUMN);

    $cats = $pdo->prepare("SELECT Upref_categ FROM userpref_cat WHERE userPrefID = ?");
    $cats->execute([$prefId]);
    $prefs['categories'] = $cats->fetchAll(PDO::FETCH_COLUMN);*/
    
    $brands = $pdo->prepare("SELECT Upref_brands FROM userpref_brands WHERE userPrefID = ?");
    $brands->execute([$prefId]);
    $prefs['brands'] = $brands->fetchAll(PDO::FETCH_COLUMN);


    $cats = $pdo->prepare("
    SELECT c.catType 
    FROM userpref_cat uc 
    JOIN categories c ON uc.Upref_categ = c.categoryID 
    WHERE uc.userPrefID = ?
    ");
    $cats->execute([$prefId]);
    $prefs['categories'] = $cats->fetchAll(PDO::FETCH_COLUMN);


    $stores = $pdo->prepare("SELECT Upref_stores FROM userpref_stores WHERE userPrefID = ?");
    $stores->execute([$prefId]);
    $prefs['stores'] = $stores->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(["status" => "success", "data" => $prefs]);

}


?>
