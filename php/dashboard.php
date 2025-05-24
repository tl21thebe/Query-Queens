<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CompareIt</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Analytics and insights for CompareIt shoe marketplace</p>
        </div>

        <!-- Key Metrics Row -->
        <div class="metrics-row">
            <div class="metric-card">
                <div class="metric-icon">👟</div>
                <div class="metric-content">
                    <h3 id="total-products">Loading...</h3>
                    <p>Total Products</p>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon">⭐</div>
                <div class="metric-content">
                    <h3 id="total-reviews">Loading...</h3>
                    <p>Total Reviews</p>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon">🏪</div>
                <div class="metric-content">
                    <h3 id="total-stores">Loading...</h3>
                    <p>Partner Stores</p>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon">🔥</div>
                <div class="metric-content">
                    <h3 id="avg-rating">Loading...</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
            <div class="chart-container">
                <h3>Brand Distribution</h3>
                <canvas id="brandChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Category Distribution</h3>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Price Analysis Row -->
        <div class="charts-row">
            <div class="chart-container full-width">
                <h3>Price Range Analysis</h3>
                <canvas id="priceChart"></canvas>
            </div>
        </div>

        <!-- Top Rated Products Section -->
        <div class="top-products-section">
            <h2>🏆 Top Rated Products</h2>
            <div class="products-grid" id="top-products-grid">
                <div class="loading">Loading top rated products...</div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="activity-section">
            <h2>📈 Recent Activity</h2>
            <div class="activity-feed" id="activity-feed">
                <div class="loading">Loading recent activity...</div>
            </div>
        </div>

        <!-- Reviews Analytics Section -->
        <div class="reviews-section">
            <h2>📊 Review Analytics</h2>
            <div class="review-stats-single">
                <h4>Review Statistics</h4>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-label">Most Reviewed Product:</span>
                        <span id="most-reviewed" class="stat-value">Loading...</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Highest Rated Brand:</span>
                        <span id="highest-rated-brand" class="stat-value">Loading...</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Most Popular Category:</span>
                        <span id="popular-category" class="stat-value">Loading...</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total User Reviews:</span>
                        <span id="total-user-reviews" class="stat-value">Loading...</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Average Product Rating:</span>
                        <span id="avg-product-rating" class="stat-value">Loading...</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Most Active Reviewer:</span>
                        <span id="top-reviewer" class="stat-value">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/dashboard.js"></script>
    <?php include('footer.php'); ?>
</body>
</html>
