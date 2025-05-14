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
     * below is a fuction that creates a table named product_store
     */

    function ensureProductStoreTable($conn) {
    
        $result = $conn->query("SHOW TABLES LIKE 'product_store'");
        if ($result->num_rows == 0) {
            
            $sql = "CREATE TABLE product_store (
                id INT NOT NULL AUTO_INCREMENT,
                product_id INT NOT NULL,
                store_id INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                PRIMARY KEY (id),
                FOREIGN KEY (product_id) REFERENCES products(product_id),
                FOREIGN KEY (store_id) REFERENCES stores(store_id)
            )";
            
            if ($conn->query($sql) === TRUE) {
                echo "Table 'product_store' created successfully.<br>";
            } else {
                throw new Exception("Error creating product_store table: " . $conn->error);
            }
        }
    }
    /*
     * The following is a function that creates a table name reviews
    */

    function ensureReviewsTable($conn) {
    
        $result = $conn->query("SHOW TABLES LIKE 'reviews'");
        if ($result->num_rows == 0) {
            
            $sql = "CREATE TABLE reviews (
                id INT NOT NULL AUTO_INCREMENT,
                product_id INT NOT NULL,
                user_id INT NOT NULL,
                rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
                description TEXT,
                review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                FOREIGN KEY (product_id) REFERENCES products(product_id),
                FOREIGN KEY (user_id) REFERENCES users(userID)
            )";
            
            if ($conn->query($sql) === TRUE) {
                echo "Table 'reviews' created successfully.<br>";
            } else {
                throw new Exception("Error creating reviews table: " . $conn->error);
            }
        }
    }
?>