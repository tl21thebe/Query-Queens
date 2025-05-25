<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
$name = $_SESSION['user']['name'];
?>

<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Preferences</title>
  <link rel="stylesheet" href="../css/preferences.css">
</head>
<body>

  <div class="preferences-container">
    <h2>Hello, <?php echo htmlspecialchars($name); ?>! Set Your Preferences</h2>
    <form id="preferences-form">

      <div class="form-group">
        <label for="min-price">Min Price (ZAR):</label>
        <input type="number" id="min-price" min="0" step="0.01" placeholder="e.g. 500">
      </div>

      <div class="form-group">
        <label for="max-price">Max Price (ZAR):</label>
        <input type="number" id="max-price" min="0" step="0.01" placeholder="e.g. 2500">
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" id="only-available">
          Only show available products
        </label>
      </div>

      <div class="form-group">
        <label for="brands">Preferred Brands:</label>
        <select id="brands" multiple></select>
      </div>

      <div class="form-group">
        <label for="categories">Preferred Categories (IDs):</label>
        <select id="categories" multiple></select>
      </div>

      <div class="form-group">
        <label for="stores">Preferred Stores (IDs):</label>
        <select id="stores" multiple></select>
      </div>

      <div class="form-actions">
        <button type="submit">Save Preferences</button>
      </div>
    </form>

    <p id="status-message"></p>
  </div>

  <script src="../js/preferences.js"></script>
</body>
</html>


<?php include('footer.php'); ?>
