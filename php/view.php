<?php include 'header.php'; ?>

<!-- Add the specific CSS for this page -->
<link rel="stylesheet" href="../css/view.css">

<section class="product-view">
    <div class="carousel">
        <img id="main-image" src="" alt="Product Image">
        <div class="image-thumbnails"></div>
    </div>
    
    <div class="product-details">
        <h1 id="product-title"></h1>
        <div class="price-rating">
            <p class="price" id="product-price"></p>
            <div class="rating" id="product-rating">
                <span class="stars"></span>
                <span class="review-count"></span>
            </div>
        </div>
        <p class="description" id="product-description"></p>
        
        <div class="details-section">
            <h3>Product Details</h3>
            <ul class="extra-features" id="extra-features"></ul>
        </div>
        
        <div class="retailers-section">
            <h3>Prices at Retailers</h3>
            <table id="retailer-prices">
                <thead>
                    <tr>
                        <th>Retailer</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <div class="reviews-section">
            <h3>Customer Reviews</h3>
            <div id="reviews-container"></div>
            <button id="add-review-btn">Add Your Review</button>
        </div>
    </div>
</section>

<script src="../js/view.js"></script>

<?php include_once 'footer.php'; ?>