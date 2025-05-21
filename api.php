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

// Accept both form-data and JSON input
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : "";
$requestData = [];

if (strpos($contentType, 'application/json') !== false) 
{
    // Handle JSON input
    $jsonInput = file_get_contents('php://input');
    $requestData = json_decode($jsonInput, true) ?: [];
    file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - JSON data: ' . $jsonInput . "\n", FILE_APPEND);
} 
else 
{
    // Handle form data (POST)
    $requestData = $_POST;
}

class API 
{
    private $db;
    private $currencyCache = [];
    private $currencyTimestamp = 0;
    private $currencyTTL = 3600; // Cache currency rates for 1 hour

   public function __construct($pdo) 
    {
        $this->db = $pdo;
        file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - API instance created with PDO: ' . 
            (is_object($pdo) ? 'valid' : 'invalid') . "\n", FILE_APPEND);
    }

  public function handleRequest($requestData) 
    {
        // Get the request method
        $method = $_SERVER['REQUEST_METHOD'];

        // Only allow POST requests
        if ($method !== 'POST') {
            $this->sendResponse(405, ['status' => 'error', 'data' => 'Method Not Allowed']);
            return;
        }

        // Debug request data
        file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Processing request data: ' . print_r($requestData, true) . "\n", FILE_APPEND);

        $type = isset($requestData['type']) ? $requestData['type'] : '';
        
        // Check API key (except for Register which doesn't need it)
        if ($type !== 'Register'  && $type !== 'Login') 
        {
            $apiKey = isset($requestData['api_key']) ? $requestData['api_key'] : '';
            if (empty($apiKey) || !$this->validateApiKey($apiKey)) 
            {
                $this->sendResponse(401, ['status' => 'error', 'data' => 'Invalid API key']);
                return;
            }
        }

       // Handle different request types
        switch ($type) 
        {
            case 'Register':
                $this->handleRegistration($requestData);
                break;
            case 'GetAllProducts':
                $this->handleGetAllProducts($requestData);
                break;
          case 'Login':
                $this->handleLogin($requestData);
                break;
            case 'Save':
                $this->handleSavePreferences($requestData);
                break;
            case 'GetPreferences':
                $this->handleGetPreferences($requestData);
                break;
            case 'Wishlist':
                $this->handleWishlist($requestData);
                break;
           default:
                $this->sendResponse(400, ['status' => 'error', 'data' => 'Invalid request type']);
                break;
        }
    }

  private function validateApiKey($apiKey) 
    {
        // Skip validation if no API key provided
        if (empty($apiKey)) 
        {
            return false;
        }

        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE api_key = :api_key");
            $stmt->bindParam(':api_key', $apiKey);
            $stmt->execute();
            $count = $stmt->fetchColumn();
        
            // Debug logging
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " - API key validation: $apiKey, count: $count\n", FILE_APPEND);
        
            return $count > 0;
        }   
        catch (PDOException $e) 
        {
            file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - API key validation error: ' . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }

  

  
          
          

      

  

    

    

    // Since the instance is already returned above (with the change), I'll comment this section
    // Just tell me what do you guys think :)
    /*
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }*/

    // I'll add more error-handling functions
    private function errorResponse($message) {
        return json_encode([
            "status" => "error",
            "timestamp" => time(),
            "data" => $message
        ]);
    }
    private function successResponse($data) {
        return json_encode([
            "status" => "success",
            "timestamp" => time(),
            "data" => $data
        ]);
    }

  //This function will validate the apiKey
  private function isValidApiKey($apikey) {
        $query = "SELECT userID FROM users WHERE apiKey = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $apikey);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

/* QUESTION: Don't you think that it'll be best to use one class only, being the 
Database? All the functions can be methods for this class. */
//=========== classes============ to split us Users,Products,Stores etc.
// I'll comment this section as we can just use one class
/*
class User
{
//signup and login functions
}

class Products 
{
  //products and views
}
*/
}
switch ($requestBody->type) //I edited this too, you can still change it though
{
   
default:
    echo json_encode([
            "status" => "error", 
            "timestamp" => time(), 
            "data" => "Unsupported request type: " . $requestBody->type
        ]);
    /* I changed this case for better logging */
}
