/**
 * Dashboard JavaScript
 * Handles data visualization and analytics for CompareIt dashboard
 */

const API_BASE_URL = "../php/api.php";


let charts = {};


document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

/**
 * Main dashboard initialization function
 */
async function initializeDashboard() {
    try {
        
        await Promise.all([
            loadMetrics(),
            loadTopRatedProducts(),
            loadChartsData(),
            loadRecentActivity(),
            loadReviewAnalytics()
        ]);
    } catch (error) {
        console.error('Error loading recent activity:', error);
        document.getElementById('activity-feed').innerHTML = 
            '<div class="error">Failed to load recent activity</div>';
    }
}

/**
 * Display activity feed
 */
function displayActivity(activities) {
    const feed = document.getElementById('activity-feed');
    
    const activitiesHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon">${activity.icon}</div>
            <div class="activity-content">
                <div class="activity-title">${activity.title}</div>
                <div class="activity-time">${activity.time}</div>
            </div>
        </div>
    `).join('');
    
    feed.innerHTML = activitiesHTML;
}

/**
 * Load review analytics
 */
async function loadReviewAnalytics() {
    try {
        const products = await getAllProducts();
        const brands = await getBrands();
        
        
        const mostReviewed = findMostReviewedProduct(products);
        const highestRatedBrand = findHighestRatedBrand(products, brands);
        const popularCategory = findMostPopularCategory(products);
        
        
        const totalReviews = Math.floor(products.length * 0.3); // Mock calculation
        const avgRating = calculateAverageRating(products);
        const topReviewer = "John D."; // Mock data
        
        
        document.getElementById('most-reviewed').textContent = mostReviewed;
        document.getElementById('highest-rated-brand').textContent = highestRatedBrand;
        document.getElementById('popular-category').textContent = popularCategory;
        document.getElementById('total-user-reviews').textContent = totalReviews;
        document.getElementById('avg-product-rating').textContent = avgRating.toFixed(1) + ' ⭐';
        document.getElementById('top-reviewer').textContent = topReviewer;
        
    } catch (error) {
        console.error('Error loading review analytics:', error);
        document.getElementById('most-reviewed').textContent = 'Error loading data';
        document.getElementById('highest-rated-brand').textContent = 'Error loading data';
        document.getElementById('popular-category').textContent = 'Error loading data';
        document.getElementById('total-user-reviews').textContent = 'Error loading data';
        document.getElementById('avg-product-rating').textContent = 'Error loading data';
        document.getElementById('top-reviewer').textContent = 'Error loading data';
    }
}

/**
 * Helper function to get all products
 */
async function getAllProducts() {
    const response = await fetch(`${API_BASE_URL}?type=getAllProducts`);
    const data = await response.json();
    
    if (data.status === 'success') {
        return data.data;
    } else {
        throw new Error(data.data || 'Failed to fetch products');
    }
}

/**
 * Helper function to get all brands
 */
async function getBrands() {
    const response = await fetch(`${API_BASE_URL}?type=getBrands`);
    const data = await response.json();
    
    if (data.status === 'success') {
        return data.data;
    } else {
        throw new Error(data.data || 'Failed to fetch brands');
    }
}

/**
 * Helper function to get all stores
 */
async function getStores() {
    const response = await fetch(`${API_BASE_URL}?type=getStores`);
    const data = await response.json();
    
    if (data.status === 'success') {
        return data.data;
    } else {
        throw new Error(data.data || 'Failed to fetch stores');
    }
}

/**
 * Calculate average rating for products
 */
function calculateAverageRating(products) {
    if (products.length === 0) return 0;
    
    // Mock calculation since we don't have actual ratings
    const ratings = products.map(() => Math.random() * 2 + 3); // Random ratings between 3-5
    const sum = ratings.reduce((a, b) => a + b, 0);
    return sum / ratings.length;
}

/**
 * Find most reviewed product
 */
function findMostReviewedProduct(products) {
    if (products.length === 0) return 'No products available';
    
    
    const popularProducts = products.filter(p => p.brand === 'Nike' || p.brand === 'Adidas');
    if (popularProducts.length > 0) {
        return popularProducts[0].name;
    }
    return products[0].name;
}

/**
 * Find highest rated brand
 */
function findHighestRatedBrand(products, brands) {
    if (brands.length === 0) return 'No brands available';
    
    
    const nikeBrand = brands.find(b => b.name === 'Nike');
    if (nikeBrand) return 'Nike';
    
    return brands[0].name;
}

/**
 * Find most popular category
 */
function findMostPopularCategory(products) {
    if (products.length === 0) return 'No categories available';
    
    const categoryCounts = {};
    products.forEach(product => {
        const category = product.category || 'Uncategorized';
        categoryCounts[category] = (categoryCounts[category] || 0) + 1;
    });
    
    let maxCount = 0;
    let mostPopular = 'Uncategorized';
    
    for (const [category, count] of Object.entries(categoryCounts)) {
        if (count > maxCount) {
            maxCount = count;
            mostPopular = category;
        }
    }
    
    return mostPopular;
}

/**
 * Generate star rating display
 */
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let stars = '';
    
    
    for (let i = 0; i < fullStars; i++) {
        stars += '★';
    }
    
    
    if (hasHalfStar) {
        stars += '☆';
    }
    
    
    for (let i = 0; i < emptyStars; i++) {
        stars += '☆';
    }
    
    return stars;
}

/**
 * Generate colors for charts
 */
function generateColors(count) {
    const colors = [
        '#FF6B9D', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
        '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
        '#F8C471', '#82E0AA', '#F1948A', '#85C1E9', '#D2B4DE'
    ];
    
    return colors.slice(0, count);
}

/**
 * Navigate to product view
 */
function viewProduct(productId) {
    window.location.href = `view.php?id=${productId}`;
}

/**
 * Show error message
 */
function showError(message) {
    console.error(message);
    
    alert(`Dashboard Error: ${message}`);
}

/**
 * Cleanup charts on page unload
 */
window.addEventListener('beforeunload', function() {
    Object.values(charts).forEach(chart => {
        if (chart && typeof chart.destroy === 'function') {
            chart.destroy();
        }
    });
});

/**
 * Refresh dashboard data
 */
function refreshDashboard() {
    
    Object.values(charts).forEach(chart => {
        if (chart && typeof chart.destroy === 'function') {
            chart.destroy();
        }
    });
    charts = {};
    
    
    initializeDashboard();
}


if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeDashboard,
        refreshDashboard,
        viewProduct
    };
}
        console.error('Error initializing dashboard:', error);
        showError('Failed to load dashboard data');
    

/**
 * Load key metrics for the dashboard
 */
async function loadMetrics() {
    try {
        
        const products = await getAllProducts();
        const brands = await getBrands();
        const stores = await getStores();
        
        
        document.getElementById('total-products').textContent = products.length;
        document.getElementById('total-stores').textContent = brands.length;
        
        
        const avgRating = calculateAverageRating(products);
        document.getElementById('avg-rating').textContent = avgRating.toFixed(1) + ' ⭐';
        
        
        document.getElementById('total-reviews').textContent = Math.floor(products.length * 0.3);
        
    } catch (error) {
        console.error('Error loading metrics:', error);
        document.getElementById('total-products').textContent = 'Error';
        document.getElementById('total-reviews').textContent = 'Error';
        document.getElementById('total-stores').textContent = 'Error';
        document.getElementById('avg-rating').textContent = 'Error';
    }
}

/**
 * Load top rated products
 */
async function loadTopRatedProducts() {
    try {
        const response = await fetch(`${API_BASE_URL}?type=GetRatedProducts`);
        const data = await response.json();
        
        if (data.status === 'success' && data.data.length > 0) {
            displayTopRatedProducts(data.data.slice(0, 6)); // Show top 6
        } else {
            
            const products = await getAllProducts();
            displayTopRatedProducts(products.slice(0, 6));
        }
    } catch (error) {
        console.error('Error loading top rated products:', error);
        document.getElementById('top-products-grid').innerHTML = 
            '<div class="error">Failed to load top rated products</div>';
    }
}

/**
 * Display top rated products in the dashboard
 */
function displayTopRatedProducts(products) {
    const grid = document.getElementById('top-products-grid');
    
    const productsHTML = products.map((product, index) => `
        <div class="product-card" onclick="viewProduct(${product.shoeID})">
            <div class="product-rank">${index + 1}</div>
            <img src="${product.image_url || '/api/placeholder/200/200'}" 
                 alt="${product.name}" 
                 class="product-image"
                 onerror="this.src='/api/placeholder/200/200'">
            <div class="product-name">${product.name}</div>
            <div class="product-brand">${product.brand_name || product.brand || 'Unknown Brand'}</div>
            <div class="product-price">R${parseFloat(product.price || 0).toFixed(2)}</div>
            <div class="product-rating">
                <span class="stars">${generateStars(4.5)}</span>
                <span class="rating-count">(${product.review_count || Math.floor(Math.random() * 50) + 10} reviews)</span>
            </div>
        </div>
    `).join('');
    
    grid.innerHTML = productsHTML;
}

/**
 * Load and create charts
 */
async function loadChartsData() {
    try {
        const products = await getAllProducts();
        const brands = await getBrands();
        
        
        createBrandChart(products, brands);
        
        
        createCategoryChart(products);
        
        
        createPriceChart(products);
        
        
        
    } catch (error) {
        console.error('Error loading charts data:', error);
    }
}

/**
 * Create brand distribution chart
 */
function createBrandChart(products, brands) {
    const ctx = document.getElementById('brandChart');
    if (!ctx) return;
    
    
    const brandCounts = {};
    products.forEach(product => {
        const brand = product.brand || 'Unknown';
        brandCounts[brand] = (brandCounts[brand] || 0) + 1;
    });
    
    const labels = Object.keys(brandCounts);
    const data = Object.values(brandCounts);
    const colors = generateColors(labels.length);
    
    charts.brandChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

/**
 * Create category distribution chart
 */
function createCategoryChart(products) {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    
    
    const categoryCounts = {};
    products.forEach(product => {
        const category = product.category || 'Uncategorized';
        categoryCounts[category] = (categoryCounts[category] || 0) + 1;
    });
    
    const labels = Object.keys(categoryCounts);
    const data = Object.values(categoryCounts);
    const colors = generateColors(labels.length);
    
    charts.categoryChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

/**
 * Create price range analysis chart
 */
function createPriceChart(products) {
    const ctx = document.getElementById('priceChart');
    if (!ctx) return;
    
    
    const priceRanges = {
        '0-500': 0,
        '501-1000': 0,
        '1001-1500': 0,
        '1501-2000': 0,
        '2000+': 0
    };
    
    products.forEach(product => {
        const price = parseFloat(product.price || 0);
        if (price <= 500) priceRanges['0-500']++;
        else if (price <= 1000) priceRanges['501-1000']++;
        else if (price <= 1500) priceRanges['1001-1500']++;
        else if (price <= 2000) priceRanges['1501-2000']++;
        else priceRanges['2000+']++;
    });
    
    charts.priceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(priceRanges).map(range => `R${range}`),
            datasets: [{
                label: 'Number of Products',
                data: Object.values(priceRanges),
                backgroundColor: 'rgba(255, 105, 180, 0.8)',
                borderColor: 'rgba(255, 105, 180, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

/**
 * Create rating distribution chart
 */
function createRatingChart() {
    const ctx = document.getElementById('ratingChart');
    if (!ctx) return;
    
    // Mock rating data since we don't have enough real reviews
    const ratingData = {
        '5 Stars': 45,
        '4 Stars': 32,
        '3 Stars': 15,
        '2 Stars': 6,
        '1 Star': 2
    };
    
    charts.ratingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(ratingData),
            datasets: [{
                label: 'Number of Reviews',
                data: Object.values(ratingData),
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545'
                ],
                borderWidth: 1,
                borderColor: '#fff',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', 
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 10,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                y: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            layout: {
                padding: {
                    top: 5,
                    bottom: 5,
                    left: 5,
                    right: 15
                }
            }
        }
    });
}

/**
 * Load recent activity feed
 */
async function loadRecentActivity() {
    try {
        // Mock recent activity data
        const activities = [
            {
                icon: '👤',
                title: 'New user registered: John Doe',
                time: '2 minutes ago'
            },
            {
                icon: '⭐',
                title: 'New review added for Nike Air Zoom Pegasus',
                time: '15 minutes ago'
            },
            {
                icon: '👟',
                title: 'Product added: Adidas Ultraboost 24',
                time: '1 hour ago'
            },
            {
                icon: '🏪',
                title: 'New store partnership: SportCheck',
                time: '2 hours ago'
            },
            {
                icon: '💰',
                title: 'Price updated for 15 products',
                time: '3 hours ago'
            },
            {
                icon: '📊',
                title: 'Weekly analytics report generated',
                time: '1 day ago'
            }
        ];
        
        displayActivity(activities);
    } catch (error) {
        console.error('Error loading recent activity:', error);
        document.getElementById('activity-feed').innerHTML = 
            '<div class="error">Failed to load recent activity</div>';
    }
}