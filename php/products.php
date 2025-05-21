<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Listing</title>
  <link rel="stylesheet" href="./cs/product.css">
  <!--<script type="module" src="js/getProducts.js"></script> //no need for this-->


</head>
<body>
  <h1>Available Products</h1>
  <div id="product-list">Loading products...</div>

  <!--<script type="module">
    import {
      getAllProducts
    } from './js/getProducts.js';

    //const apiKey = '9cbcb909999ccabff7d78c7495581dee'; We will use a valid apiKey once we have one in the users table

    async function loadProducts() {
      const container = document.getElementById('product-list');
      try {
        const products = await getAllProducts();

        if (products.length === 0) {
          container.innerHTML = "<p>No products found.</p>";
          return;
        }

        container.innerHTML = '';
        products.forEach(product => {
          const productDiv = document.createElement('div');
          productDiv.classList.add('product');

          productDiv.innerHTML = `
            <div class="product-title">${product.title}</div>
            <div><strong>Brand:</strong> ${product.brand}</div>
            <div><strong>Available:</strong> ${product.is_available ? 'Yes' : 'No'}</div>
            <div><strong>Categories:</strong> ${product.categories}</div>
            <div><strong>Description:</strong> ${product.description}</div>
          `;

          container.appendChild(productDiv);
            //<img src="${product.image_url}" alt="${product.title}" onerror="this.src='images/default.jpg'">

        });

      } catch (error) {
        container.innerHTML = `<p>Error loading products: ${error.message}</p>`;
      }
    }

    loadProducts();
  </script>-->
  <script type="module">
  import { getAllProducts } from './js/product.js';

  async function loadProducts() {
    const container = document.getElementById('product-list');
    try {
      const products = await getAllProducts();

      container.innerHTML = '';
      products.forEach(product => {
        container.innerHTML += `
          <div>
            <strong>${product.name}</strong> - ${product.brand} - R${product.price}
            <br><img src="${product.image_url}" width="100">
            <hr>
          </div>
        `;
      });

    } catch (error) {
      container.innerHTML = `<p>Error loading products: ${error.message}</p>`;
    }
  }

  loadProducts();
</script>
  
</body>
</html>

<?php include('footer.php'); ?>

