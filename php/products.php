<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compare Shoe Prices - Product Listing</title>
  <link rel="stylesheet" href="../css/products.css">
</head>
<body>

  <!-- Slogan Section -->
  <div class="slogan-section">
    <div class="slogan-text">
      <h1>Step Into Your Dreams</h1>
      <p>Find the perfect pair at the perfect price - Where every step counts!</p>
    </div>

     <!-- Search Container in Slogan Section -->
    <div class="search-container">
      <input type="text" id="main-search" placeholder="Search for your perfect shoes...">
      <button onclick="handleMainSearch()">🔍</button>
    </div>
  </div>

  <!-- Page Header -->
  <div class="page-header">
    <h1>Compare Shoe Prices</h1>
    <p>Find and compare the best prices on your favorite shoes</p>
  </div>

   <!-- Filters Section -->
  <div class="filters-section">
    <h3>Filter Products</h3>
    <div class="filters-row">
      <div class="filter-group">
        <label for="search-bar">Search:</label>
        <input type="text" id="search-bar" placeholder="Search for shoes...">
      </div>
      
       <div class="filter-group">
        <label for="category-filter">Category:</label>
        <select id="category-filter">
          <option value="">All Categories</option>
        </select>
      </div>
      
      <div class="filter-group">
        <label for="brand-filter">Brand:</label>
        <select id="brand-filter">
          <option value="">All Brands</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="min-price">Min Price (ZAR):</label>
        <input type="number" id="min-price" placeholder="0" min="0">
      </div>
      
      <div class="filter-group">
        <label for="max-price">Max Price (ZAR):</label>
        <input type="number" id="max-price" placeholder="No limit" min="0">
      </div>
    </div>
    
    <div class="filter-actions">
      <button onclick="applyFilters()" class="btn btn-primary">Apply Filters</button>
      <button onclick="clearFilters()" class="btn btn-secondary">Clear Filters</button>
    </div>
  </div>

  <!-- Products Container -->
  <div class="products-holder">Loading products...</div>

  <script src="../js/products.js"></script>
</body>
</html>
<?php include('footer.php'); ?>
      




