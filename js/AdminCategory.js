document.addEventListener("DOMContentLoaded", () => {
    const actionContainer = document.getElementById("form-container");

    document.getElementById("add-btn").addEventListener("click", () => {
        renderForm("addCategory");
    });

    document.getElementById("edit-btn").addEventListener("click", () => {
        renderForm("updateCategory");
    });

    document.getElementById("delete-btn").addEventListener("click", () => {
        renderForm("deleteCategory");
    });

    function renderForm(action) {
        const actionTitle = action === "addCategory" ? "ADD" : action === "updateCategory" ? "EDIT" : "DELETE";

        let formHtml = `<form id="category-form"><h2>${actionTitle} Category</h2>`;

        if (action !== "addCategory") {
            formHtml += `
                <label for="categoryID">Select Category *</label>
                <select name="categoryID" id="categoryID" required>
                    <option value="">Loading categories...</option>
                </select>
            `;
        }

        if (action !== "deleteCategory") {
            formHtml += `
                <label for="catType">Category Type</label>
                <input type="text" name="catType" id="catType" required>
            `;
        }

        formHtml += `<button type="submit">${actionTitle}</button></form>`;
        actionContainer.innerHTML = formHtml;

        if (action !== "addCategory") {
            loadCategoriesDropdown();
        }

        document.getElementById("category-form").addEventListener("submit", (e) => {
            e.preventDefault();
            handleSubmit(action);
        });
    }

    function handleSubmit(action) {
        const formData = new FormData(document.getElementById("category-form"));
        const payload = {};

        for (let [key, value] of formData.entries()) {
            if (value.trim() !== "") {
                payload[key] = sanitize(value);
            }
        }

        payload["type"] = action;

        fetch("../api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            console.log("API Response:", data);
            alert(data.data || "Action completed.");
        })
        .catch(err => {
            console.error("Request failed", err);
            alert("Request failed: " + err.message);
        });
    }

    function loadCategoriesDropdown() {
        fetch("../api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ type: "getCategories" })
        })
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("categoryID");
            select.innerHTML = "";

            if (data.status === "success" && Array.isArray(data.data)) {
                data.data.forEach(category => {
                    const option = document.createElement("option");
                    option.value = category.id || category.categoryID;
                    option.textContent = category.type || category.catType || category.name;
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = "<option value=''>No categories found</option>";
            }
        })
        .catch(err => {
            console.error("Failed to load categories", err);
            const select = document.getElementById("categoryID");
            select.innerHTML = "<option value=''>Error loading categories</option>";
        });
    }

    function sanitize(input) {
        return input.replace(/['";]/g, "").trim();
    }
});
