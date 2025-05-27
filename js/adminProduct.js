document.addEventListener("DOMContentLoaded", () => {
    const actionContainer = document.getElementById("form-container");

    document.getElementById("add-btn").addEventListener("click", () => renderForm("add"));
    document.getElementById("edit-btn").addEventListener("click", () => renderForm("edit"));
    document.getElementById("delete-btn").addEventListener("click", () => renderForm("delete"));

    async function renderForm(action) {
        const [products, categories, brands] = await Promise.all([
            fetchData("getAllProducts"),
            fetchData("getCategories"),
            fetchData("getBrands")
        ]);

        const actionTitleMap = {
            add: "Add Shoe",
            edit: "Edit Shoe",
            delete: "Delete Shoe"
        };

        const buttonLabelMap = {
            add: "Add",
            edit: "Update",
            delete: "Delete"
        };

        let formHtml = `<form id="shoe-form"><h2>${actionTitleMap[action]}</h2>`;

        if (action !== "add") {
            formHtml += `
                <label for="shoeID">Select Shoe *</label>
                <select name="shoeID" id="shoeID" required>
                    <option value="">-- Select Shoe --</option>
                    ${products.map(p => `<option value="${p.shoeID}">${p.name} (ID: ${p.shoeID})</option>`).join('')}
                </select>
            `;
        }

        if (action !== "delete") {
            const required = action === "add" ? "required" : "";

            formHtml += `
                <label for="categoryID">Category *</label>
                <select name="categoryID" id="categoryID" ${required}>
                    <option value="">-- Select Category --</option>
                   ${categories.map(c => `<option value="${c.categoryID}">${c.catType}</option>`).join('')}
                </select>

                <label for="name">Name</label>
                <input type="text" name="name" id="name" ${required}>

                <label for="brandID">Brand *</label>
                <select name="brandID" id="brandID" ${required}>
                    <option value="">-- Select Brand --</option>
                    ${brands.map(b => `<option value="${b.brandID}">${b.name}</option>`).join('')}
                </select>

                <label for="price">Price</label>
                <input type="number" name="price" id="price" step="0.01" ${required}>

                <label for="releaseDate">Release Date</label>
                <input type="date" name="releaseDate" id="releaseDate" ${required}>

                <label for="description">Description</label>
                <textarea name="description" id="description" ${required}></textarea>

                <label for="material">Material</label>
                <input type="text" name="material" id="material" ${required}>

                <label for="gender">Gender</label>
                <select name="gender" id="gender" ${required}>
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Prefer not to say">Prefer not to say</option>
                </select>

                <label for="image_url">Image URL</label>
                <input type="url" name="image_url" id="image_url" ${required}>

                <label for="size_range">Size Range</label>
                <input type="text" name="size_range" id="size_range">

                <label for="colour">Colour</label>
                <input type="text" name="colour" id="colour" ${required}>

                <label for="Upref_stores">User Preferred Stores</label>
                <input type="number" name="Upref_stores" id="Upref_stores">
            `;
        }

        formHtml += `<button type="submit">${buttonLabelMap[action]}</button></form>`;
        actionContainer.innerHTML = formHtml;

        document.getElementById("shoe-form").addEventListener("submit", (e) => {
            e.preventDefault();
            handleSubmit(action);
        });
    }

function handleSubmit(action) {
    const form = document.getElementById("shoe-form");
    const formData = new FormData(form);
    const payload = {};

    for (let [key, value] of formData.entries()) {
        const trimmed = sanitize(value);
        if (key === "name" && trimmed === "") {
            alert("Shoe name cannot be empty.");
            return;
        }
        payload[key] = trimmed;
    }

    payload["type"] = action + "Product";

    fetch("../api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            alert(data.data);
        } else {
            alert("API Error: " + data.data);
        }
    })
    .catch(err => {
        console.error("Request failed:", err);
        alert("Request failed: " + err.message);
    });
}
    async function fetchData(type) {
        const res = await fetch(`../api.php?type=${type}`);
        const data = await res.json();
        return data.status === "success" ? data.data : [];
    }

    function sanitize(input) {
        return input.replace(/['";]/g, "").trim();
    }
});
