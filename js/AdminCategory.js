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
                <label for="categoryID">Category ID *</label>
                <input type="number" name="categoryID" id="categoryID" required>
            `;
        }

        if (action !== "deleteCategory") {
            const required = "required";
            formHtml += `
                <label for="catType">Category Type</label>
                <input type="text" name="catType" id="catType" ${required}>
            `;
        }

        formHtml += `<button type="submit">${actionTitle}</button></form>`;
        actionContainer.innerHTML = formHtml;

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
            alert(data.data || "Action completed.");
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
