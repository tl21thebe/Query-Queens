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

        if (action !== "addBrand") {
            formHtml += `
                <label for="brandID">Brand ID *</label>
                <input type="number" name="brandID" id="brandID" required>
            `;
        }

        if (action !== "deleteBrand") {
            const required = "required";
            formHtml += `
                <label for="name">Brand Name</label>
                <input type="text" name="name" id="name" ${required}>
            `;
        }

        formHtml += `<button type="submit">${actionTitleMap[action]}</button></form>`;
        actionContainer.innerHTML = formHtml;

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

        fetch("../php/sabiraV_api.php", {
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

    function sanitize(input) {
        return input.replace(/['";]/g, "").trim();
    }
});
