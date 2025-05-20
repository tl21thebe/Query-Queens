<?php
session_start();

//you can change the structure 

header("Content-Type: application/json");
require_once __DIR__.'/config.php';

// ========== DB Singleton Class ========== database connection
class Database { 
    private static $instance = null;
    public $conn;

    private function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}

//=========== classes============ to split us Users,Products,Stores etc.
class User
{
//signup and login functions
}

class Products 
{
  //products and views
}

switch ($input['type']) //endpoints
{
   
default:
sendResponse(404, ['error' => 'Endpoint not found']);
}
