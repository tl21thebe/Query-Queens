/**
 * getProducts.js
 * 
 * This file manages product information retrieval and filtering from the API.
 * It provides functions to fetch products and process product-related operations.
 */

// Base URL for API requests
const API_BASE_URL = "api.php";

/**
 * Fetch all available products
 * 
 * @param {string} apiKey - User API key for authentication
 * @returns {Promise} - Promise that resolves to product data
 */
async function getAllProducts(apiKey) {
  try {
    const response = await fetch(`${API_BASE_URL}?endpoint=getAllProducts`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${apiKey}`
      }
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.error || "Failed to fetch products");
    }

    const data = await response.json();
    return data.products;
  } catch (error) {
    console.error("Error fetching products:", error);
    throw error;
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
      method: "GET",
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
async function getProductsByBrand(apiKey, brand) {
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

// Export all functions
export {
  getAllProducts,
  getProductById,
  getProductsByCategory,
  getProductsByAvailability,
  searchProducts,
  updateProductAvailability,
  getProductCategories,
  getProductsByBrand
};
