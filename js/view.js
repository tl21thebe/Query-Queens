// Update view.js with this enhanced version
document.addEventListener("DOMContentLoaded", function() {
    const productId = getProductIdFromURL();
    if (productId) {
        fetchProductDetails(productId);
    }
});

function getProductIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

async function fetchProductDetails(productId) {
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                endpoint: 'getProductById',
                product_id: productId
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch product details');
        }

        const data = await response.json();
        if (data.status === "success" && data.product) {
            displayProductDetails(data.product);
        } else {
            console.error("Product not found", data);
            document.getElementById('product-title').textContent = "Product not found";
        }
    } catch (error) {
        console.error("Error fetching product:", error);
        document.getElementById('product-title').textContent = "Error loading product";
    }
}

function displayProductDetails(product) {
    // Main image and details
    const mainImage = document.getElementById("main-image");
    mainImage.src = product.image_url || 'images/default.jpg';
    mainImage.alt = product.title;

    document.getElementById("product-title").textContent = product.title;
    document.getElementById("product-price").textContent = `ZAR ${product.final_price}`;
    document.getElementById("product-description").textContent = product.description;

    // Extra features
    const extraFeaturesList = document.getElementById("extra-features");
    extraFeaturesList.innerHTML = "";

    // Add basic product info
    const basicDetails = [
        `Brand: ${product.brand}`,
        `Category: ${product.categories}`,
        `Availability: ${product.is_available ? 'In Stock' : 'Out of Stock'}`
    ];

    basicDetails.forEach(detail => {
        const li = document.createElement("li");
        li.textContent = detail;
        extraFeaturesList.appendChild(li);
    });

    // Add retailer prices if available
    if (product.retailer_prices && product.retailer_prices.length > 0) {
        const priceHeader = document.createElement("li");
        priceHeader.innerHTML = "<strong>Prices at Retailers:</strong>";
        extraFeaturesList.appendChild(priceHeader);
        
        product.retailer_prices.forEach(retailer => {
            const li = document.createElement("li");
            li.textContent = `${retailer.name}: ZAR ${retailer.price}`;
            extraFeaturesList.appendChild(li);
        });
    }

    // Add ratings if available
    if (product.ratings) {
        const ratingLi = document.createElement("li");
        ratingLi.innerHTML = `<strong>Rating:</strong> ${product.ratings.average} (${product.ratings.count} reviews)`;
        extraFeaturesList.appendChild(ratingLi);
    }

    // Add reviews if available
    if (product.reviews && product.reviews.length > 0) {
        const reviewHeader = document.createElement("li");
        reviewHeader.innerHTML = "<strong>Customer Reviews:</strong>";
        extraFeaturesList.appendChild(reviewHeader);
        
        product.reviews.forEach(review => {
            const reviewLi = document.createElement("li");
            reviewLi.innerHTML = `
                <div class="review">
                    <strong>${review.user}</strong> - ${review.rating} stars
                    <p>${review.comment}</p>
                    <small>${review.date}</small>
                </div>
            `;
            extraFeaturesList.appendChild(reviewLi);
        });
    }

    // Setup image carousel if multiple images exist
    if (product.images && product.images.length > 1) {
        setupImageCarousel(product.images);
    }
}

function setupImageCarousel(images) 
{
    const thumbnailsContainer = document.querySelector(".image-thumbnails");
    thumbnailsContainer.innerHTML = "";

    images.forEach((image, index) => 
    {
        const img = document.createElement("img");
        img.src = image;
        img.alt = `Product Image ${index + 1}`;
        img.classList.add("thumbnail");
        img.addEventListener("click", () => {
            document.getElementById("main-image").src = image;
        });
        thumbnailsContainer.appendChild(img);
    });
}