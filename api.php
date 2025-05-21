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

        // Get request type - use the API spec format from Task 4
        $type = isset($requestData['type']) ? $requestData['type'] : '';
        
        // Check API key (except for Register which doesn't need it)
        if ($type !== 'Register'  && $type !== 'Login') 
        {
            $apiKey = isset($requestData['api_key']) ? $requestData['api_key'] : '';
            if (empty($apiKey) || !$this->validateApiKey($apiKey)) {
                $this->sendResponse(401, ['status' => 'error', 'data' => 'Invalid API key']);
                return;
            }
        }
        

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

     private function handleLogin($requestData) 
    {
        $email = isset($requestData['email']) ? $requestData['email'] : '';
        $password = isset($requestData['password']) ? $requestData['password'] : '';
        
        if (empty($email) || empty($password)) 
        {
            $this->sendResponse(400, ['status' => 'error', 'message' => 'Email and password are required']);
            return;
        }
        
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $this->sendResponse(401, ['status' => 'error', 'message' => 'Invalid email or password']);
                return;
            }
            
            // Verify password
            list($salt, $hash) = explode(':', $user['password']);
            $passwordHash = hash('sha256', $salt . $password);
            
            if ($passwordHash !== $hash) {
                $this->sendResponse(401, ['status' => 'error', 'message' => 'Invalid email or password']);
                return;
            }
            
            // Return success with API key
            $this->sendResponse(200, [
                'status' => 'success',
                'timestamp' => time() * 1000,
                'data' => [
                    'api_key' => $user['api_key'],
                    'email' => $user['email'],
                    'name' => $user['name']
                ]
            ]);
            
        } catch (PDOException $e) {
            $this->sendResponse(500, ['status' => 'error', 'message' => 'Database error']);
        }
    }

    private function handleRegistration($requestData) 
    {
        // Get registration data
        $name = isset($requestData['name']) ? $requestData['name'] : '';
        $surname = isset($requestData['surname']) ? $requestData['surname'] : '';
        $email = isset($requestData['email']) ? $requestData['email'] : '';
        $password = isset($requestData['password']) ? $requestData['password'] : '';
        $type = isset($requestData['user_type']) ? $requestData['user_type'] : ''; 
        
        // Debug registration data
        file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Registration data: ' . 
            "name=$name, surname=$surname, email=$email, type=$type" . "\n", FILE_APPEND);

        // Validate data
        if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($type)) {
            $this->sendResponse(400, ['status' => 'error', 'message' => 'All fields are required']);
            return;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(400, ['status' => 'error', 'message' => 'Invalid email format']);
            return;
        }

        // Validate password
        if (!$this->validatePassword($password)) {
            $this->sendResponse(400, ['status' => 'error', 'message' => 'Password does not meet requirements']);
            return;
        }

        // Check if the email already exists
        if ($this->emailExists($email)) {
            $this->sendResponse(409, ['status' => 'error', 'message' => 'Email already registered']);
            return;
        }

        // Hash the password
        $hashedPassword = $this->hashPassword($password);
        
        // Generate API key
        $apiKey = $this->generateApiKey();

        // Insert user into the database
        if ($this->insertUser($name, $surname, $email, $hashedPassword, $type, $apiKey)) {
            // Send success response
            $this->sendResponse(200, [
                'status' => 'success',
                'timestamp' => time() * 1000,
                'data' => [
                    'api_key' => $apiKey
                ]
            ]);
        } else {
            $this->sendResponse(500, ['status' => 'error', 'message' => 'Failed to register user']);
        }
    }

    //not too sure about the function to get the products, people in backend will modify this function according to the shoes table
    private function handleGetAllProducts($requestData) 
    {
        
    }

     private function handleGetPreferences($requestData) 
     {
        // Validate API key
        $apiKey = isset($requestData['api_key']) ? $requestData['api_key'] : '';
        if (empty($apiKey) || !$this->validateApiKey($apiKey)) 
        {
            $this->sendResponse(401, ['status' => 'error', 'message' => 'Invalid API key']);
            return;
        }
    
        // Get user ID from API key
        $userId = $this->getUserIdFromApiKey($apiKey);
        if (!$userId) 
        {
            $this->sendResponse(401, ['status' => 'error', 'message' => 'User not found']);
            return;
        }
    
        try {
            $stmt = $this->db->prepare("SELECT currency, filters FROM user_preferences WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $preferences = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($preferences) 
            {
                // Decode JSON filters
                $preferences['filters'] = $preferences['filters'] ? json_decode($preferences['filters'], true) : null;
                $this->sendResponse(200, [
                    'status' => 'success',
                    'timestamp' => time() * 1000,
                    'data' => $preferences
                ]);
            } else {
                // Return default preferences if none saved
                $this->sendResponse(200, 
                [
                    'status' => 'success',
                    'timestamp' => time() * 1000,
                    'data' => [
                        'currency' => 'ZAR',
                        'filters' => null
                    ]
                ]);
            }
        } 
        catch (PDOException $e) 
        {
            file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Get preferences error: ' . $e->getMessage() . "\n", FILE_APPEND);
            $this->sendResponse(500, ['status' => 'error', 'message' => 'Failed to get preferences']);
        }
    }

    private function handleSavePreferences($requestData) {
        // Validate API key
        $apiKey = $requestData['api_key'] ?? '';
        $userId = $this->getUserIdFromApiKey($apiKey);
    
        if (!$userId) 
        {
            $this->sendResponse(401, ['status' => 'error', 'message' => 'Invalid API key']);
            return;
    }
        // Extract preferences
        $currency = isset($requestData['currency']) ? strtoupper($requestData['currency']) : 'ZAR';
        $filters = isset($requestData['filters']) ? $requestData['filters'] : null;
    
        try {
            // Check if preferences exist
            $stmt = $this->db->prepare("SELECT id FROM user_preferences WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                // Update existing preferences
                $stmt = $this->db->prepare("UPDATE user_preferences SET currency = :currency, filters = :filters WHERE user_id = :user_id");
            } else {
                // Insert new preferences
                $stmt = $this->db->prepare("INSERT INTO user_preferences (user_id, currency, filters) VALUES (:user_id, :currency, :filters)");
            }
            
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':currency', $currency);
            $stmt->bindValue(':filters', $filters ? json_encode($filters) : null);
            $stmt->execute();
    
            $this->sendResponse(200, [
                'status' => 'success',
                'timestamp' => time() * 1000,
                'data' => 'Preferences saved successfully'
            ]);
        } catch (PDOException $e) {
            file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Save preferences error: ' . $e->getMessage() . "\n", FILE_APPEND);
            $this->sendResponse(500, ['status' => 'error', 'message' => 'Failed to save preferences']);
        }
    }

    private function getUserIdFromApiKey($apiKey) 
    {
        try 
        {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE api_key = :api_key");
            $stmt->bindParam(':api_key', $apiKey);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) 
        {
            file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - Get user ID error: ' . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }

    private function sanitizeField($field) 
    {
        // Remove any non-alphanumeric characters except underscores
        return preg_replace('/[^a-zA-Z0-9_]/', '', $field);
    }


            