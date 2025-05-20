<?php
//import the dump from the drive. Upload T's mysqldump because it has the
//populated data
$host = 'localhost'; 
$dbname = 'queryqueens_compareit'; 
$user = 'root'; //Your username
$pass = ''; //enter your mysqlworkbench password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode([
        "status" => "error",
        "timestamp" => time() * 1000,
        "data" => "Database connection failed: " . $e->getMessage()
    ]));
}
?>

<!--this is the config that we are going to use to connect to the database :
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

okay bye :) -->

