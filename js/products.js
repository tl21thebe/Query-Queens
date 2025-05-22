/**
 * getProducts.js
 * 
 * This file manages product information retrieval and filtering from the API.
 * It provides functions to fetch products and process product-related operations.
 */

// Base URL for API requests
const API_BASE_URL = "../php/api.php";//just saying that you may need to adjust the path when testing because i placed the api in my php folders

/**
 * Fetch all available products
 * 
 * @param {string} apiKey - User API key for authentication
 * @returns {Promise} - Promise that resolves to product data
 */
async function getAllProducts() 
{
    try {
        const response = await fetch(`${API_BASE_URL}?type=getAllProducts`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            return data.data;
        } else {
            throw new Error(data.data || "Failed to fetch products");
        }

    } catch (error) {
        console.error("Error fetching products:", error);
        throw error;
    }
}

/**
 * Fetch top rated products from the API
 * 
 * @returns {Promise} - Promise that resolves to rated product data
 */
async function getRatedProducts() 
{
    try {
        const response = await fetch(`${API_BASE_URL}?type=GetRatedProducts`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            return data.data;
        } else {
            throw new Error(data.data || "Failed to fetch rated products");
        }

    } catch (error) {
        console.error("Error fetching rated products:", error);
        throw error;
    }
}

/**
 * Add a new product (requires API key)
 * 
 * @param {Object} productData - Product information
 * @returns {Promise} - Promise that resolves to add result
 */
async function addProduct(productData) {
    try {
        const response = await fetch(API_BASE_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${getApiKey()}`
            },
            body: JSON.stringify({
                type: 'addProduct',
                name: productData.name,
                price: productData.price,
                brandID: productData.brandID,
                categoryID: productData.categoryID,
                image_url: productData.image_url || '',
                description: productData.description || ''
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            return data;
        } else {
            throw new Error(data.data || "Failed to add product");
        }

    } catch (error) {
        console.error("Error adding product:", error);
        throw error;
    }
}

/**
 * Edit an existing product (requires API key)
 * 
 * @param {Object} productData - Product information including shoeID
 * @returns {Promise} - Promise that resolves to edit result
 */
async function editProduct(productData) {
    try {
        const response = await fetch(API_BASE_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${getApiKey()}`
            },
            body: JSON.stringify({
                type: 'editProduct',
                shoeID: productData.shoeID,
                name: productData.name,
                price: productData.price,
                description: productData.description || '',
                image_url: productData.image_url || ''
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            return data;
        } else {
            throw new Error(data.data || "Failed to edit product");
        }

    } catch (error) {
        console.error("Error editing product:", error);
        throw error;
    }
}

/**
 * Delete a product (requires API key)
 * 
 * @param {number} shoeID - ID of the product to delete
 * @returns {Promise} - Promise that resolves to delete result
 */
async function deleteProduct(shoeID) {
    try {
        const response = await fetch(API_BASE_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${getApiKey()}`
            },
            body: JSON.stringify({
                type: 'deleteProduct',
                shoeID: shoeID
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            return data;
        } else {
            throw new Error(data.data || "Failed to delete product");
        }

    } catch (error) {
        console.error("Error deleting product:", error);
        throw error;
    }
}

/**
 * Get all brands
 * 
 * @returns {Promise} - Promise that resolves to brands data
 */
async function getBrands() {
    try {
        const response = await fetch(`${API_BASE_URL}?type=getBrands`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            return data.data;
        } else {
            throw new Error(data.data || "Failed to fetch brands");
        }

    } catch (error) {
        console.error("Error fetching brands:", error);
        throw error;
    }
}

/**
 * Filter products by brand
 * 
 * @param {string} brandName - Brand name to filter by
 * @returns {Promise} - Promise that resolves to filtered product data
 */
async function getProductsByBrand(brandName) {
    try {
        const allProducts = await getAllProducts();
        return allProducts.filter(product => 
            product.brand && product.brand.toLowerCase() === brandName.toLowerCase()
        );
    } catch (error) {
        console.error(`Error filtering products by brand ${brandName}:`, error);
        throw error;
    }
}

/**
 * Filter products by category
 * 
 * @param {string} categoryName - Category name to filter by
 * @returns {Promise} - Promise that resolves to filtered product data
 */
async function getProductsByCategory(categoryName) {
    try {
        const allProducts = await getAllProducts();
        return allProducts.filter(product => 
            product.category && product.category.toLowerCase() === categoryName.toLowerCase()
        );
    } catch (error) {
        console.error(`Error filtering products by category ${categoryName}:`, error);
        throw error;
    }
}

/**
 * Search products by name or brand
 * 
 * @param {string} searchTerm - Term to search for
 * @returns {Promise} - Promise that resolves to search results
 */
async function searchProducts(searchTerm) {
    try {
        const allProducts = await getAllProducts();
        const searchTermLower = searchTerm.toLowerCase();
        
        return allProducts.filter(product => 
            (product.name && product.name.toLowerCase().includes(searchTermLower)) ||
            (product.brand && product.brand.toLowerCase().includes(searchTermLower))
        );
    } catch (error) {
        console.error(`Error searching products for "${searchTerm}":`, error);
        throw error;
    }
}

/**
 * Display products in the UI using the proper card styling
 * 
 * @param {Array} products - Array of product objects
 */
function displayProducts(products) {
    const productsHolder = document.querySelector('.products-holder');
    
    if (!products || products.length === 0) {
        productsHolder.innerHTML = '<p>No products found.</p>';
        return;
    }

    const productsHTML = products.map(product => `
        <div class="product-card" data-product-id="${product.shoeID}">
            <div class="product-image">
                ${product.image_url ? 
                    `<img src="${product.image_url}" alt="${product.name}" onerror="this.src='/api/placeholder/300/180'">` : 
                    `<div class="no-image">No Image Available</div>`
                }
            </div>
            <div class="product-info">
                <div class="product-title">${product.name}</div>
                <div class="product-brand"><strong>Brand:</strong> ${product.brand || 'Unknown Brand'}</div>
                <div class="product-category"><strong>Category:</strong> ${product.category || 'Uncategorized'}</div>
                <div class="price-container">
                    <span class="price">R${parseFloat(product.price || 0).toFixed(2)}</span>
                </div>
            </div>
            <div class="product-actions">
                <button onclick="viewProduct(${product.shoeID})" class="view-btn">View Details</button>
                <button onclick="compareProduct(${product.shoeID})" class="compare-btn">Compare</button>
            </div>
        </div>
    `).join('');

    productsHolder.innerHTML = `<div class="product-list">${productsHTML}</div>`;
}

/**
 * Handle main search from the slogan section
 */
async function handleMainSearch() {
    const searchTerm = document.getElementById('main-search').value;
    if (searchTerm.trim()) {
        // Scroll to products section
        const productsSection = document.querySelector('.page-header');
        productsSection.scrollIntoView({ behavior: 'smooth' });
        
        // Set the search term in the filter search bar
        document.getElementById('search-bar').value = searchTerm;
        
        // Apply the search
        await applyFilters();
    }
}


/**
 * Fetch product by ID
 * 
 * @param {string} apiKey - User API key for authentication
 * @param {number} productId - ID of the product to retrieve
 * @returns {Promise} - Promise that resolves to product data
 */
async function getProductById(apiKey, productId) {
  try {
    const response = await fetch(`${API_BASE_URL}?endpoint=getProductById`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${apiKey}`
      },
      body: JSON.stringify({ product_id: productId })
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || "Failed to fetch product");
    }

    const data = await response.json();
    return data.product;
  } catch (error) {
    console.error(`Error fetching product ${productId}:`, error);
    throw error;
  }
}

/**
 * Filter products by category
 * 
 * @param {string} apiKey - User API key for authentication
 * @param {string} category - Category to filter by
 * @returns {Promise} - Promise that resolves to filtered product data
 */
async function getProductsByCategory(apiKey, category) {
  try {
    const allProducts = await getAllProducts(apiKey);
    return allProducts.filter(product => product.categories.includes(category));
  } catch (error) {
    console.error(`Error filtering products by category ${category}:`, error);
    throw error;
  }
}

/**
 * Filter products by availability
 * 
 * @param {string} apiKey - User API key for authentication
 * @param {boolean} isAvailable - Availability status to filter by
 * @returns {Promise} - Promise that resolves to filtered product data
 */
async function getProductsByAvailability(apiKey, isAvailable) {
  try {
    const allProducts = await getAllProducts(apiKey);
    return allProducts.filter(product => product.is_available === isAvailable);
  } catch (error) {
    console.error(`Error filtering products by availability:`, error);
    throw error;
  }
}

/**
 * Search products by name/title
 * 
 * @param {string} apiKey - User API key for authentication
 * @param {string} searchTerm - Term to search for in product titles
 * @returns {Promise} - Promise that resolves to search results
 */
async function searchProducts(apiKey, searchTerm) {
  try {
    const allProducts = await getAllProducts(apiKey);
    const searchTermLower = searchTerm.toLowerCase();
    
    return allProducts.filter(product => 
      product.title.toLowerCase().includes(searchTermLower) ||
      product.description.toLowerCase().includes(searchTermLower) ||
      product.brand.toLowerCase().includes(searchTermLower)
    );
  } catch (error) {
    console.error(`Error searching products for "${searchTerm}":`, error);
    throw error;
  }
}

/**
 * Update product availability status
 * 
 * @param {string} apiKey - User API key for authentication
 * @param {number} productId - ID of the product to update
 * @param {boolean} isAvailable - New availability status
 * @returns {Promise} - Promise that resolves to update result
 */
async function updateProductAvailability(apiKey, productId, isAvailable) {
  try {
    const response = await fetch(`${API_BASE_URL}?endpoint=updateProduct`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${apiKey}`
      },
      body: JSON.stringify({
        product_id: productId,
        is_available: isAvailable ? 1 : 0
      })
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || "Failed to update product availability");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error(`Error updating product ${productId} availability:`, error);
    throw error;
  }
}

/**
 * Get distinct product categories
 * 
 * @param {string} apiKey - User API key for authentication
 * @returns {Promise} - Promise that resolves to array of unique categories
 */
async function getProductCategories(apiKey) {
  try {
    const allProducts = await getAllProducts(apiKey);
    const categoriesSet = new Set();
    
    allProducts.forEach(product => {
      const productCategories = product.categories.split(',');
      productCategories.forEach(category => categoriesSet.add(category.trim()));
    });
    
    return Array.from(categoriesSet);
  } catch (error) {
    console.error("Error fetching product categories:", error);
    throw error;
  }
}

/**
 * Get products by brand
 * 
 * @param {string} apiKey - User API key for authentication
 * @param {string} brand - Brand name to filter by
 * @returns {Promise} - Promise that resolves to filtered product data
 */
async function getProductsByBrand(apiKey, brand) 
{
  try {
    const allProducts = await getAllProducts(apiKey);
    return allProducts.filter(product => 
      product.brand.toLowerCase() === brand.toLowerCase()
    );
  } catch (error) {
    console.error(`Error filtering products by brand ${brand}:`, error);
    throw error;
  }
}

function renderProductList(products, container) 
{
  container.innerHTML = '';
  
  if (products.length === 0) 
  {
    container.innerHTML = '<p>No products found.</p>';
    return;
  }

  products.forEach(product => {
    const productElement = document.createElement('div');
    productElement.className = 'product-item';
    productElement.innerHTML = `
      <div class="product-image">
        <img src="${product.image_url}" alt="${product.title}" onerror="this.src='images/default.jpg'">
      </div>
      <div class="product-info">
        <h3>${product.title}</h3>
        <p class="brand">${product.brand}</p>
        <p class="price">ZAR ${product.final_price}</p>
        <p class="description">${product.description.substring(0, 100)}...</p>
        <button class="view-details" data-id="${product.id}">View Details</button>
      </div>
    `;
    
    container.appendChild(productElement);
  });

  // Add event listeners to all view buttons
  document.querySelectorAll('.view-details').forEach(button => {
    button.addEventListener('click', (e) => {
      const productId = e.target.getAttribute('data-id');
      window.location.href = `view.php?id=${productId}`;
    });
  });
}

// Export all functions
export 
{
  getAllProducts,
  getProductById,
  getProductsByCategory,
  getProductsByAvailability,
  searchProducts,
  updateProductAvailability,
  getProductCategories,
  getProductsByBrand,
  renderProductList
};
