<?php
/*
okay here is what we decided on , i think T menitoned that we are no longer going to use mock data anymore and the api that they provided
but now we are going to create our own products based on the theme , we decided on shoes
becuase with shoes we can compare the different brands such as:
    nike
    adidas
    cotton on :) personal favourite 
    hoka
    cloud 
and then we can compare the different categories such as :
    running :) personal fav
    casual
    heel
    sandals
    slippers
    and so forth 

Sabira mentioned that we can use her details for her wheatley and her php Admin so i think she will be responsible for this file 

okay bye :) 

if(session_status() == PHP_SESSION_NONE)//create a session that can be included in the header.php which is included in every other file so that we dont need to start the session at the beginning of evry file 
{
    session_start();
}*/

$host = 'localhost'; // This works ON wheatley. Don't change to 'wheatley.cs.up.ac.za'
$dbname = 'u23591481_queryqueens_compareIt'; // Your DB name (must start with your student number)
$user = 'u23591481'; // Your student number
$pass = 'YFOQ5R4KUUEFVGAZR2FQDGWRJT6LE5Z3'; // Find this in the password file on Wheatley

try 
{

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) 
{
    die(json_encode([
        "status" => "error",
        "timestamp" => time() * 1000,
        "data" => "Database connection failed: " . $e->getMessage()
    ]));
}
?>




