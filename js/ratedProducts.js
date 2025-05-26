document.addEventListener("DOMContentLoaded", () => {
    fetch('../api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'type=GetRatedProducts'
    })
    .then(response => response.json())
    .then(result => {
        const container = document.getElementById('rated-products');
        container.innerHTML = ''; 

        if (result.status !== "success" || !Array.isArray(result.data) || result.data.length === 0) {
            container.innerHTML = "<p>No rated products found.</p>";
            return;
        }

        result.data.forEach(product => {
            const card = document.createElement('div');
            card.className = 'product-card';

            card.innerHTML = `
                <img src="${product.image_url}" alt="${product.name}" style="max-width: 100%; height: auto;">
                <h3>${product.name}</h3>
                <p><strong>Brand:</strong> ${product.brand_name}</p>
                <p><strong>Price:</strong> R${product.price}</p>
                <p><strong>Material:</strong> ${product.material}</p>
                <p><strong>Color:</strong> ${product.colour}</p>
                <p><strong>Size Range:</strong> ${product.size_range}</p>
                <p><strong>Gender:</strong> ${product.gender}</p>
                <p><strong>Rating:</strong> ${product.avg_rating} ⭐ (${product.review_count} reviews)</p>
                <p><em>Review:</em> ${product.description}</p>
            `;

            container.appendChild(card);
        });
    })
    .catch(err => {
        console.error('Failed to fetch rated products:', err);
        const container = document.getElementById('rated-products');
        container.innerHTML = "<p>Something went wrong while fetching rated products.</p>";
    });
});
