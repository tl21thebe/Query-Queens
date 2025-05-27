/**
 * products.js
 * 
 * This file manages product display and interaction with the API.
 * Updated to match the actual API structure and database schema.
 */

// Base URL for API requests
const API_BASE_URL = "../api.php";

// Get API key from localStorage (set during login)
function getApiKey() {
    return localStorage.getItem('apiKey') || '';
}

/**
 * Fetch all available products from the API
 * 
 * @returns {Promise} - Promise that resolves to product data
 */

document.addEventListener("DOMContentLoaded", async () => {
  try {
    const allProducts = await getAllProducts(); 
    populateFilters(allProducts);             
    await loadUserPreferences(allProducts);  
      
    const sortOrder = document.getElementById('sort-order');
    if (sortOrder) sortOrder.addEventListener('change', () => applyFilters(allProducts));
      
  } catch (err) {
    console.error("Failed to initialize filters:", err);
  }
});


async function getAllProducts() {
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
async function getRatedProducts() {
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
 * View product details (placeholder function)
 */
function viewProduct(productId) 
{
    console.log('Viewing product:', productId);
    window.location.href = `view.php?id=${productId}`;
}

/**
 * Get unique categories from products
 * 
 * @param {Array} products - Array of product objects
 * @returns {Array} - Array of unique categories
 */
function getUniqueCategories(products) {
    const categories = new Set();
    products.forEach(product => {
        if (product.category) {
            categories.add(product.category);
        }
    });
    return Array.from(categories);
}

/**
 * Get unique brands from products
 * 
 * @param {Array} products - Array of product objects
 * @returns {Array} - Array of unique brands
 */
function getUniqueBrands(products) {
    const brands = new Set();
    products.forEach(product => {
        if (product.brand) {
            brands.add(product.brand);
        }
    });
    return Array.from(brands);
}

/**
 * Populate filter dropdowns
 * 
 * @param {Array} products - Array of product objects
 */
/*function populateFilters(products) {
  // Categories
  const catFilter = document.getElementById('category-filter');
  const categories = [...new Set(products.map(p => ({ id: p.categoryID, name: p.category })))];

  catFilter.innerHTML = '<option value="">All Categories</option>';
  categories.forEach(cat => {
    if (cat.id)
      catFilter.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
  });

  // Brands
  const brandFilter = document.getElementById('brand-filter');
  const brands = [...new Set(products.map(p => ({ id: p.brandID, name: p.brand })))];

  brandFilter.innerHTML = '<option value="">All Brands</option>';
  brands.forEach(brand => {
    if (brand.id)
      brandFilter.innerHTML += `<option value="${brand.id}">${brand.name}</option>`;
  });
}*/

function populateFilters(products) {

    const categoryFilter = document.getElementById('category-filter');
    const categories = getUniqueCategories(products);
    
    categoryFilter.innerHTML = '<option value="">All Categories</option>';
    categories.forEach(category => {
        categoryFilter.innerHTML += `<option value="${category}">${category}</option>`;
    });

    const brandFilter = document.getElementById('brand-filter');
    const brands = getUniqueBrands(products);
    
    brandFilter.innerHTML = '<option value="">All Brands</option>';
    brands.forEach(brand => {
        brandFilter.innerHTML += `<option value="${brand}">${brand}</option>`;
    });
}

/**
 * Apply filters to products
 */
async function applyFilters() {
    try {
        const searchTerm = document.getElementById('search-bar').value.toLowerCase();
        const selectedCategory = document.getElementById('category-filter').value;
        const selectedBrand = document.getElementById('brand-filter').value;
        const minPrice = parseFloat(document.getElementById('min-price').value) || 0;
        const maxPrice = parseFloat(document.getElementById('max-price').value) || Infinity;

        const allProducts = await getAllProducts();
        
        const filteredProducts = allProducts.filter(product => {

            ////////----------converted partt--------//////
            const productCategory = String(product.categoryID || product.category); // Ensure type match
      const productBrand = String(product.brandID || product.brand);
      //////--------------------------------------------//////

            // Search filter
            const matchesSearch = !searchTerm || 
                (product.name && product.name.toLowerCase().includes(searchTerm)) ||
                (product.brand && product.brand.toLowerCase().includes(searchTerm));
            
            // Category filter
            const matchesCategory = !selectedCategory || product.category === selectedCategory;
            
            // Brand filter
            const matchesBrand = !selectedBrand || product.brand === selectedBrand;
            
            // Price filter
            const productPrice = parseFloat(product.price) || 0;
            const matchesPrice = productPrice >= minPrice && productPrice <= maxPrice;
            
            return matchesSearch && matchesCategory && matchesBrand && matchesPrice;
        });

        // Get sort order from the DOM
        const sortOrder = document.getElementById('sort-order')?.value || '';

        // Sort products
        if (sortOrder === 'name-asc') {
            filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
        } else if (sortOrder === 'name-desc') {
            filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
        } else if (sortOrder === 'price-asc') {
            filteredProducts.sort((a, b) => (parseFloat(a.price) || 0) - (parseFloat(b.price) || 0));
        } else if (sortOrder === 'price-desc') {
            filteredProducts.sort((a, b) => (parseFloat(b.price) || 0) - (parseFloat(a.price) || 0));
        }

        displayProducts(filteredProducts);
    } catch (error) {
        console.error('Error applying filters:', error);
    }
}

/**
 * Clear all filters and show all products
 */
async function clearFilters() {
    document.getElementById('search-bar').value = '';
    document.getElementById('category-filter').value = '';
    document.getElementById('brand-filter').value = '';
    document.getElementById('min-price').value = '';
    document.getElementById('max-price').value = '';
    
    try {
        const products = await getAllProducts();
        displayProducts(products);
    } catch (error) {
        console.error('Error clearing filters:', error);
    }
}

///applies the savepreferences endpoint---------TAKEE THIS OUTTT(because we dont need it) ---------/////////////
async function saveCurrentFiltersAsPreferences() {
  const minPrice = document.getElementById("min-price").value;
  const maxPrice = document.getElementById("max-price").value;
  const category = document.getElementById("category-filter").value;
  const brand = document.getElementById("brand-filter").value;

  const categories = category ? [parseInt(category)] : [];
  const brands = brand ? [brand] : [];

  try {
    const res = await fetch("../api.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        type: "savePreferences",
        min_price: minPrice,
        max_price: maxPrice,
        only_available: false, //Or grab this from a checkbox if needed
        brands,
        categories,
        stores: [] 
      })
    });

    const result = await res.json();
    alert(result.data || "Preferences saved");
  } catch (err) {
    console.error("Error saving preferences:", err);
    alert("Failed to save preferences.");
  }
}


/**
 * Handle search functionality
 */
async function handleSearch() {
    await applyFilters();
}

/**
 * Initialize the products page
 */
async function initializeProductsPage() {
    try {
        const products = await getAllProducts();
        displayProducts(products);
        populateFilters(products);
    } catch (error) {
        console.error('Failed to load products:', error);
        const productsHolder = document.querySelector('.products-holder');
        productsHolder.innerHTML = '<p>Failed to load products. Please try again later.</p>';
    }
}

/**
 * Add event listeners and initialize page when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeProductsPage();
    
    // Add event listeners for real-time search
    const searchBar = document.getElementById('search-bar');
    if (searchBar) {
        searchBar.addEventListener('input', debounce(applyFilters, 300));
    }
    
    // Main search functionality
    const mainSearch = document.getElementById('main-search');
    if (mainSearch) {
        mainSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleMainSearch();
            }
        });
    }
    
    // Add event listeners for filter changes
    const categoryFilter = document.getElementById('category-filter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', applyFilters);
    }
    
    const brandFilter = document.getElementById('brand-filter');
    if (brandFilter) {
        brandFilter.addEventListener('change', applyFilters);
    }
    
    const minPrice = document.getElementById('min-price');
    if (minPrice) {
        minPrice.addEventListener('input', debounce(applyFilters, 500));
    }
    
    const maxPrice = document.getElementById('max-price');
    if (maxPrice) {
        maxPrice.addEventListener('input', debounce(applyFilters, 500));
    }
});

/**
 * Debounce function to limit API calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export functions for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        getAllProducts,
        getRatedProducts,
        addProduct,
        editProduct,
        deleteProduct,
        getBrands,
        getProductsByBrand, 
        getProductsByCategory,
        searchProducts,
        displayProducts
    };
}

async function loadUserPreferences() {
    try {
        const res = await fetch('../api.php?type=getPreferences');
        const result = await res.json();
        if (result.status === 'success' && result.data) {
            const prefs = result.data;

            // Set filters
            if (prefs.min_price !== null) document.getElementById('min-price').value = prefs.min_price;
            if (prefs.max_price !== null) document.getElementById('max-price').value = prefs.max_price;

            const brandFilter = document.getElementById('brand-filter');
            if (prefs.brands?.length) {
                for (const option of brandFilter.options) {
                    if (prefs.brands.includes(option.value)) {
                        option.selected = true;
                    }
                }
            }

            const categoryFilter = document.getElementById('category-filter');
            if (prefs.categories?.length) {
                for (const option of categoryFilter.options) {
                    if (prefs.categories.includes(option.value)) {
                        option.selected = true;
                    }
                }
            }

            // Optional: Apply the filters after setting them
            applyFilters();
        }
    } catch (err) {
        console.error("Error loading user preferences:", err);
    }
}
