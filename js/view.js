document.addEventListener("DOMContentLoaded", function() 
{
    const productId = getProductIdFromURL();
    if (productId) 
    {
        fetchProductDetails(productId);
    }
});

function getProductIdFromURL() 
{
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id'); 
    return productId;
}

function fetchProductDetails(productId) 
{
    const url = 'api.php';
    const data = JSON.stringify({
        studentnum: STUDENT_NUM,
        apikey: API_KEY,
        type: 'GetAllProducts',
        search:
        {
            id: productId
        },
        return: "*",
        limit: 1
    });

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if(xhttp.readyState == 4)
        {
            if(xhttp.status == 200)
            {
                const response = JSON.parse(xhttp.responseText);
                if(response.status == "success" && response.data.length > 0)
                {
                    displayProductDetails(response.data[0]);
                }
                else
                {
                    console.error("Error, product not found", response);
                }
            }
            else
            {
                console.error("API request failed with status:", xhttp.status);
            }
        }
    };

    xhttp.open('POST', url, true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(data);
}

// Function to display product details
function displayProductDetails(product) 
{
    // Main image and details
    const mainImage = document.getElementById("main-image");
    mainImage.src = product.image_url;
    mainImage.alt = product.title;

    document.getElementById("product-title").textContent = product.title;
    document.getElementById("product-price").textContent = `ZAR ${product.final_price}`;
    document.getElementById("product-description").textContent = product.description;

    // Extra features
    const extraFeaturesList = document.getElementById("extra-features");
    extraFeaturesList.innerHTML = ""; // Clear previous content

    const additionalDetails = 
    [
        `Category: ${product.department}`,
        `Brand: ${product.brand}`,
    ];

    // If extra_features exists and is an array
    if (product.extra_features && Array.isArray(product.extra_features)) 
    {
        product.extra_features.forEach(feature => {
            const li = document.createElement("li");
            li.textContent = feature;
            extraFeaturesList.appendChild(li);
        });
    }

    //first ensure if it is an array:
    if (product.extra_features && Array.isArray(product.extra_features)) 
    {
        product.extra_features.forEach(feature => 
        {
            const li = document.createElement("li");
            li.textContent = feature;
            extraFeaturesList.appendChild(li);
        });
    }

    // Add additional details
    additionalDetails.forEach(detail => 
    {
        const li = document.createElement("li");
        li.textContent = detail;
        extraFeaturesList.appendChild(li);
    });

    // Ensure images is an array
    let images;
    if (Array.isArray(product.images)) 
    {
        images = product.images; // If it's already an array, use it
    } 
    else 
    {
        images = [product.images]; // If it's not an array, wrap it in an array
    }
}

// Function to set up image carousel
function setupImageCarousel(images) 
{
    const thumbnailsContainer = document.querySelector(".image-thumbnails");
    thumbnailsContainer.innerHTML = ""; // Clear previous thumbnails

    if (images.length == 0) 
    {
        return;
    }

    // Create a main image element
    const mainImage = document.getElementById("main-image");
    mainImage.src = images[0]; // Set the first image as the main image

    images.forEach((image, index) => {
        const img = document.createElement("img");
        img.src = image;
        img.alt = `Product Image ${index + 1}`;
        img.classList.add("thumbnail");
        img.addEventListener("click", () => 
        {
            mainImage.src = image; // Change main image on thumbnail click
        });
        thumbnailsContainer.appendChild(img);
    });
}