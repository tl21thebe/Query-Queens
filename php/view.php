<?php
require_once 'config.php';

$conn = connectDB();

$shoeId = $_GET['id'] ?? null;
if (!$shoeId) {
    die("No product ID provided.");
}

// Get product info
$stmt = $conn->prepare("SELECT * FROM shoes WHERE shoeID = ?");
$stmt->bind_param("i", $shoeId);
$stmt->execute();
$productResult = $stmt->get_result();
$product = $productResult->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Product not found.");
}

// Get store prices
$stmt2 = $conn->prepare("
    SELECT s.name AS store_name, pp.price
    FROM stores_products pp
    JOIN stores s ON pp.storeID = s.storeID
    WHERE pp.shoeID = ?
");
$stmt2->bind_param("i", $shoeId);
$stmt2->execute();
$pricesResult = $stmt2->get_result();
$stmt2->close();
$conn->close();
?>




<!DOCTYPE html>
<html>
<head>
  <title><?php echo htmlspecialchars($product['name']); ?> - Product View</title>
  <link rel="stylesheet" href="view.css">
</head>

<body>
  <div class="product-view">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <img src="<?php echo $product['image_url']; ?>" alt="Product Image" width="300" onerror="this.src='images/default.jpg';">
    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

    <h2>Available Prices</h2>
    <p style="color:red;">
  Debug: Found <?php echo $pricesResult->num_rows; ?> price entries.
</p>

    <ul>
      <?php while ($row = $pricesResult->fetch_assoc()): ?>
        <li><strong><?php echo htmlspecialchars($row['store_name']); ?></strong>: R<?php echo number_format($row['price'], 2); ?></li>
      <?php endwhile; ?>
    </ul>
  </div>
</body>

</html>
