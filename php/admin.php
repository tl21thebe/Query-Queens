<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Shoe</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

  <h1>Add a New Shoe</h1>

  <form id="add-shoe-form" action="add_shoe.php" method="POST" enctype="multipart/form-data">
    
    <label for="categoryID">Category ID:</label>
    <input type="number" name="categoryID" id="categoryID" required><br><br>
    
    <label for="name">Shoe Name:</label>
    <input type="text" name="name" id="name" required><br><br>
    
    <label for="brandID">Brand ID:</label>
    <input type="number" name="brandID" id="brandID" required><br><br>
    
    <label for="price">Price ($):</label>
    <input type="number" name="price" id="price" step="0.01" required><br><br>
    
    <label for="releaseDate">Release Date:</label>
    <input type="date" name="releaseDate" id="releaseDate" required><br><br>
    
    <label for="description">Description:</label><br>
    <textarea name="description" id="description" rows="4" cols="50" required></textarea><br><br>
    
    <label for="material">Material:</label>
    <input type="text" name="material" id="material" required><br><br>
    
    <label for="gender">Gender:</label>
    <select name="gender" id="gender">
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="Prefer not to say">Prefer not to say</option>
    </select><br><br>
    
    <label for="image_url">Image URL:</label>
    <input type="text" name="image_url" id="image_url" required><br><br>
    
    <label for="size_range">Size Range:</label>
    <input type="text" name="size_range" id="size_range" placeholder="e.g. 5-12" required><br><br>
    
    <label for="colour">Colour:</label>
    <input type="text" name="colour" id="colour" required><br><br>
    
    <label for="Upref_stores">Preferred Store ID:</label>
    <input type="number" name="Upref_stores" id="Upref_stores"><br><br>
    
    <button type="submit">Add Shoe</button>
  </form>

</body>
</html>
