<?php include 'header.php'; 
?>

<!-- Add the specific CSS for this page -->
<link rel="stylesheet" href="../css/view.css">

<section class="product-view">
    <div class="carousel">
        <img id="main-image" src="" alt="Product Image">
        <div class="image-thumbnails"></div>
    </div>
    
    <div class="product-details">
        <h1 id="product-title"></h1>
        <p class="price" id="product-price"></p>
        <p class="description" id="product-description"></p>
        
        <ul class="extra-features" id="extra-features"></ul>
    </div>
</section>

<script src="../js/view.js"></script>

<?php include_once 'footer.php'; ?>
