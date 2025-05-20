<?php
session_start();

//you can change the structure 

header("Content-Type: application/json");
include("PATH TO CONFIG/php/config.php"); /* This is just a temporary path, we'll change
                                             properly later*/

// ========== DB Singleton Class ========== database connection
class Database { 
    private $conn;

    /* I edited the singleton instance a bit. Just tell what you guys think */
    public static function instance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new Database();
        }
        return $instance;
    }

    private function __construct() {
        global $conn;
        /* I've added this for error handling incase the Database can't connect */
        if (!$conn) {
            error_log("Database connection is NULL in API class constructor.");
            die(json_encode(["status" => "error", "timestamp" => time(), "data" => "Database connection failed."]));
        }
        $this->conn = $conn;
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
}

/* QUESTION: Don't you think that it'll be best to use one class only, being the 
Database? All the functions can be methods for this class. */
//=========== classes============ to split us Users,Products,Stores etc.
class User
{
//signup and login functions
}

class Products 
{
  //products and views
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
