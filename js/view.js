// Base URL for local API requests
const API_BASE_URL = "../php/api.php";

document.addEventListener("DOMContentLoaded", function() {
    const productId = getProductIdFromURL();
    if (productId) {
        fetchProductDetails(productId);
    } else {
        console.error("No product ID found in URL");
        displayError("Product not found");
    }
});



function getProductIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id');
}

async function fetchProductDetails(productId) {
    try {
        const response = await fetch(API_BASE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                 type: 'getSingleProduct',
                shoeID: productId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            displayProductDetails(data.data);
        } else {
            throw new Error(data.data || 'Failed to fetch product details');
        }
    } catch (error) {
        console.error('Error fetching product details:', error);
        displayError('Failed to load product details');
    }

       
}

function displayProductDetails(product) 
{
    // Set main image
    const mainImage = document.getElementById("main-image");
    if (product.image_url) {
        mainImage.src = product.image_url;
        mainImage.alt = product.name;
    } else {
        mainImage.src = '/api/placeholder/400/300';
        mainImage.alt = 'No image available';
    }

     // Set product information
    document.getElementById("product-title").textContent = product.name || 'Unknown Product';
    document.getElementById("product-price").textContent = `R${parseFloat(product.price || 0).toFixed(2)}`;
    document.getElementById("product-description").textContent = product.description || 'No description available';

    // Set rating information
    const ratingContainer = document.getElementById("product-rating");
    const starsContainer = ratingContainer.querySelector('.stars');
    const reviewCountContainer = ratingContainer.querySelector('.review-count');
    
    if (product.avg_rating) {
        starsContainer.innerHTML = generateStarRating(product.avg_rating);
        reviewCountContainer.textContent = `(${product.review_count} reviews)`;
    } else {
        starsContainer.innerHTML = 'No ratings yet';
        reviewCountContainer.textContent = '';
    }

    // Set extra features/details
    const extraFeaturesList = document.getElementById("extra-features");
    extraFeaturesList.innerHTML = "";

    const productDetails = [
        `Brand: ${product.brand_name || 'Unknown'}`,
        `Category: ${product.category_name || 'Uncategorized'}`,
        `Material: ${product.material || 'Not specified'}`,
        `Gender: ${product.gender || 'Unisex'}`,
        `Color: ${product.colour || 'Not specified'}`,
        `Size Range: ${product.size_range || 'Not specified'}`,
        `Release Date: ${product.releaseDate ? new Date(product.releaseDate).toLocaleDateString() : 'Not specified'}`
    ];

    productDetails.forEach(detail => {
        const li = document.createElement("li");
        li.textContent = detail;
        extraFeaturesList.appendChild(li);
    });

    // Display store prices
    displayStorePrices(product.stores || []);

    // Display reviews
    displayReviews(product.reviews || []);

    // Store current product for other functions
    window.currentProduct = product;

}

function displayStorePrices(stores) {
    const tableBody = document.querySelector('#retailer-prices tbody');
    tableBody.innerHTML = '';

    if (stores.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="3">No store information available</td></tr>';
        return;
    }

    stores.forEach(store => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${store.store_name}</td>
            <td>R${parseFloat(store.store_price || 0).toFixed(2)}</td>
            <td>
                <span class="stock-status in-stock">In Stock</span>
                <a href="mailto:${store.store_email}" class="contact-store">Contact Store</a>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function displayReviews(reviews) {
    const reviewsContainer = document.getElementById('reviews-container');
    reviewsContainer.innerHTML = '';

    if (reviews.length === 0) {
        reviewsContainer.innerHTML = '<p>No reviews available for this product.</p>';
        return;
    }

    reviews.forEach(review => {
        const reviewElement = document.createElement('div');
        reviewElement.classList.add('review-item');
        reviewElement.innerHTML = `
            <div class="review-header">
                <span class="reviewer-name">${review.reviewer_name || 'Anonymous'}</span>
                <span class="review-rating">${generateStarRating(review.rating)}</span>
                <span class="review-date">${new Date(review.review_date).toLocaleDateString()}</span>
            </div>
            <div class="review-text">${review.review_text || 'No review text provided'}</div>
        `;
        reviewsContainer.appendChild(reviewElement);
    });
}

function generateStarRating(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    let stars = '';
    
    // Full stars
    for (let i = 0; i < fullStars; i++) {
        stars += '★';
    }
    
    // Half star
    if (hasHalfStar) {
        stars += '☆';
    }
    
    // Empty stars
    for (let i = 0; i < emptyStars; i++) {
        stars += '☆';
    }
    
    return `${stars} (${rating})`;
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