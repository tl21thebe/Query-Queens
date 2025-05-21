<?php include 'header.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rated Products</title>
    <link rel="stylesheet" href="../css/products.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="products-container">
    <h2>Top Rated Products</h2>
    <div class="products-grid">

        <!-- Sample Product Card -->
        <div class="product-card">
            <img src="../assets/product1.jpg" alt="Product 1">
            <h3>Product Name 1</h3>
            <p class="rating">★★★★☆</p>
            <p>$199.99</p>
        </div>

        <div class="product-card">
            <img src="../assets/product2.jpg" alt="Product 2">
            <h3>Product Name 2</h3>
            <p class="rating">★★★★★</p>
            <p>$89.99</p>
        </div>

        <!-- more products will be added here dynamically -->

    </div>
</div>

</body>
</html>

<?php include 'footer.php' ?>