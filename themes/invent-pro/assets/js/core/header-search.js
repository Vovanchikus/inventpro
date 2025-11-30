document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("warehouse-search");
    const productList = document.getElementById("product-list");

    if (!searchInput || !productList) return;

    const products = Array.from(
        productList.querySelectorAll(".warehouse__item")
    );

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase().trim();

        products.forEach((item) => {
            const name =
                item
                    .querySelector(".warehouse__name")
                    ?.textContent.toLowerCase() || "";
            const number =
                item
                    .querySelector(".warehouse__number")
                    ?.textContent.toLowerCase() || "";

            if (name.includes(query) || number.includes(query)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });
});
