document.addEventListener("DOMContentLoaded", () => {
    const actionContainer = document.getElementById("form-container");

    document.getElementById("add-btn").addEventListener("click", () => {
        renderForm("addBrand");
    });

    document.getElementById("edit-btn").addEventListener("click", () => {
        renderForm("updateBrand");
    });

    document.getElementById("delete-btn").addEventListener("click", () => {
        renderForm("deleteBrand");
    });

    function renderForm(action) {
        const actionTitleMap = {
            addBrand: "Add Brand",
            updateBrand: "Edit Brand",
            deleteBrand: "Delete Brand"
        };

        let formHtml = `<form id="brand-form"><h2>${actionTitleMap[action]}</h2>`;

        // For edit and delete: show dropdown to select brand
        if (action !== "addBrand") {
            formHtml += `
                <label for="brandID">Select Brand *</label>
                <select name="brandID" id="brandID" required>
                    <option value="">Loading brands...</option>
                </select>
            `;
        }

        // For add and edit: show brand name input
        if (action !== "deleteBrand") {
            formHtml += `
                <label for="name">Brand Name</label>
                <input type="text" name="name" id="name" required>
            `;
        }

        formHtml += `<button type="submit">${actionTitleMap[action]}</button></form>`;
        actionContainer.innerHTML = formHtml;

        if (action !== "addBrand") {
            loadBrandsDropdown(action);
        }

        // Autofill brand name input when editing
        if (action === "updateBrand") {
            document.getElementById("brandID").addEventListener("change", (e) => {
                const selectedID = e.target.value;
                const selectedOption = e.target.selectedOptions[0];
                document.getElementById("name").value = selectedOption ? selectedOption.dataset.name : "";
            });
        }

        document.getElementById("brand-form").addEventListener("submit", (e) => {
            e.preventDefault();
            handleSubmit(action);
        });
    }

    function handleSubmit(action) {
        const formData = new FormData(document.getElementById("brand-form"));
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
            const message = typeof data.data === "object" && data.data.message
                ? data.data.message
                : data.data;
            alert(message || "Action completed.");
        })
        .catch(err => {
            console.error("Request failed", err);
            alert("Request failed: " + err.message);
        });
    }

    function loadBrandsDropdown(action) {
        fetch("../api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ type: "getBrands" })
        })
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("brandID");
            select.innerHTML = "";

            if (data.status === "success" && Array.isArray(data.data)) {
                data.data.forEach(brand => {
                    const option = document.createElement("option");
                    option.value = brand.brandID;
                    option.textContent = brand.name;
                    // store brand name in data attribute for autofill
                    option.dataset.name = brand.name;
                    select.appendChild(option);
                });

                // If editing, prefill brand name input with first option
                if (action === "updateBrand") {
                    const firstOption = select.options[0];
                    if (firstOption) {
                        document.getElementById("name").value = firstOption.dataset.name;
                    }
                }

            } else {
                select.innerHTML = "<option value=''>No brands found</option>";
            }
        })
        .catch(err => {
            console.error("Failed to load brands", err);
            const select = document.getElementById("brandID");
            select.innerHTML = "<option value=''>Error loading brands</option>";
        });
    }

    function sanitize(input) {
        return input.replace(/['";]/g, "").trim();
    }
});
