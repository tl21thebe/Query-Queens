<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Listing</title>
  <link rel="stylesheet" href="./css/product.css">
  <!-- <link rel="stylesheet" href="../css/product.css"> -->
  <!--<script type="module" src="js/getProducts.js"></script> //no need for this-->


</head>
<body>
  <h1>Available Products</h1>
  <div id="product-list">Loading products...</div>
  <script type="module">
  import { getAllProducts } from '../js/product.js';

  async function loadProducts() 
  {
    const container = document.getElementById('product-list');
    try 
    {
      const products = await getAllProducts();
      renderProductList(products, container);
    } 
    catch (error) 
    {
      container.innerHTML = `<p>Error loading products: ${error.message}</p>`;
    }
  }
  loadProducts();
</script>
  
</body>
</html>

<?php include('footer.php'); ?>

