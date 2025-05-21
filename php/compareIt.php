<?php
include_once 'header.php';?>

<link rel="stylesheet" href="compareIt.css">

    <div class="search-filter-container">
    <div class="search-container">
        <input type="text" id="search-bar" placeholder="Search products...">
    </div>

    <div class="sort-container">
        <select id="sort-select">
            <option value="title">Sort by Title</option>
            <option value="final_price">Sort by Price</option>
            <option value="brand">Sort by Brand</option>
        </select>
    </div>

    <div class="filter-container">
        <input type="number" id="min-price" placeholder="Min Price">
        <input type="number" id="max-price" placeholder="Max Price">
        <select id="category-filter">
            <option value="">All Categories</option>
            <!-- Categories will be populated via the API-->
        </select>
         <select id="brand-filter">
            <!-- Brands will be populated via the API-->
        </select>
        <button onclick="applyFilters()">Apply Filters</button>
    </div>

    <!-- Products container -->
    <div class="products-holder"></div>

    <script src="compareIt.js"></script>

<?php include_once 'footer.php';?>



