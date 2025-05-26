document.addEventListener("DOMContentLoaded", () => {
  const brandSelect = document.getElementById("brands");
  const categorySelect = document.getElementById("categories");
  const storeSelect = document.getElementById("stores");

  async function populateSelect(selectEl, endpoint, valueField, labelField) {
    try {
      const res = await fetch(`../api.php?type=${endpoint}`);
      const data = await res.json();
      if (data.status === "success") {
        selectEl.innerHTML = "";
        data.data.forEach(item => {
          const option = document.createElement("option");
          option.value = item[valueField];
          option.textContent = item[labelField];
          selectEl.appendChild(option);
        });
      }
    } catch (err) {
      console.error(`Failed to load ${endpoint}:`, err);
    }
  }

  populateSelect(brandSelect, "getBrands", "brandID", "name");
  populateSelect(categorySelect, "getCategories", "categoryID", "catType");
  populateSelect(storeSelect, "getStores", "storeID", "name");

  document.getElementById("preferences-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const minPrice = document.getElementById("min-price").value;
    const maxPrice = document.getElementById("max-price").value;
    const onlyAvailable = document.getElementById("only-available").checked;

    const brands = Array.from(brandSelect.selectedOptions).map(opt => opt.value);
    const categories = Array.from(categorySelect.selectedOptions).map(opt => parseInt(opt.value));
    const stores = Array.from(storeSelect.selectedOptions).map(opt => parseInt(opt.value));

    try {
      const res = await fetch("../api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          type: "savePreferences",
          min_price: minPrice,
          max_price: maxPrice,
          only_available: onlyAvailable,
          brands,
          categories,
          stores
        })
      });

      //const result = await res.json();
      const rawText=await res.text();
      console.log("Raw response:", rawText); 
    const result = JSON.parse(rawText);
      document.getElementById("status-message").textContent = result.data;
    } catch (err) {
      console.error("Error saving preferences:", err);
      document.getElementById("status-message").textContent = "Failed to save preferences.";
    }
  });
});
