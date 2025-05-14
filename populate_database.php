<?php

    //Database Configuration
    /**
     * In this section of the code, the database configuration
     * will differ from person to person as we each will host the
     * database locally. This also makes it more secure as the 
     * database credentials will not be vulnerable to third-party
     * hacking
     */

    $db_host = '#';
    $db_user = '#'; 
    $db_password = '#'; 
    $db_name = '#';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected to database successfully.<br>";

    //This is where the store data is declared :)
    $stores_added = 0;

    try{
        createStores($conn, $stores_added);
        echo "Stores added: $stores_added<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    function createStores($conn, &$stores_added) {
        echo "Creating stores...<br>";

    $result = $conn->query("SELECT COUNT(*) as count FROM stores");
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        echo "Stores already exist in the database. Skipping store creation.<br>";
        return;
    }

    /**I created mock store data as I'm not entirely sure if the API will fill this.
     * Please correct where necessary and update as I'm not sure if this is correct
     */
 
    $stores = [
        ['name' => 'Tech Galaxy', 'email' => 'sales@techgalaxy.com', 'phoneNumber' => '0123456789', 'store_type' => 'Online'],
        ['name' => 'Gadget World', 'email' => 'info@gadgetworld.com', 'phoneNumber' => '0123456780', 'store_type' => 'Online'],
        ['name' => 'ElectroMart', 'email' => 'support@electromart.com', 'phoneNumber' => '0123456781', 'store_type' => 'In-store'],
        ['name' => 'Fashion Hub', 'email' => 'contact@fashionhub.com', 'phoneNumber' => '0123456782', 'store_type' => 'Online'],
        ['name' => 'Home Essentials', 'email' => 'service@homeessentials.com', 'phoneNumber' => '0123456783', 'store_type' => 'In-store'],
        ['name' => 'SuperMart', 'email' => 'help@supermart.com', 'phoneNumber' => '0123456784', 'store_type' => 'In-store'],
        ['name' => 'Digital Dreams', 'email' => 'support@digitaldreams.com', 'phoneNumber' => '0123456785', 'store_type' => 'Online'],
        ['name' => 'BudgetBuy', 'email' => 'info@budgetbuy.com', 'phoneNumber' => '0123456786', 'store_type' => 'Online'],
        ['name' => 'Luxury Lane', 'email' => 'vip@luxurylane.com', 'phoneNumber' => '0123456787', 'store_type' => 'In-store'],
        ['name' => 'Game Stop', 'email' => 'games@gamestop.com', 'phoneNumber' => '0123456788', 'store_type' => 'Online']
    ];

    //The prepared SQL statement that inserts the data since, the schema was already created
    $stmt = $conn->prepare("INSERT INTO stores (name, email, phoneNumber, store_type) VALUES (?, ?, ?, ?)");
    
    foreach ($stores as $store) {
        $stmt->bind_param("ssss", $store['name'], $store['email'], $store['phoneNumber'], $store['store_type']);
        if ($stmt->execute()) {
            $stores_added++;
        } else {
            echo "Error adding store {$store['name']}: " . $stmt->error . "<br>";
        }
    }
    
    echo "$stores_added stores created successfully.<br>";
    }
    /**This is what I could accomplish so far.
     * Please add and create more functions.
     */
?>