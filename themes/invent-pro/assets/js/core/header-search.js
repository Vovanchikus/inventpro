document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("warehouse-search");
    const clearSearch = document.getElementById("clearSearch"); // крестик
    const productList = document.getElementById("product-list");

    if (!searchInput || !productList) return;

    const products = Array.from(
        productList.querySelectorAll(".warehouse__item")
    );

    // Функция фильтрации
    const filterProducts = () => {
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

        // Показываем или скрываем крестик
        if (clearSearch) {
            clearSearch.style.display = query ? "flex" : "none";
        }
    };

    // Вызываем фильтрацию при вводе
    searchInput.addEventListener("input", filterProducts);

    // Крестик для очистки
    if (clearSearch) {
        clearSearch.addEventListener("click", () => {
            searchInput.value = "";
            filterProducts();
            searchInput.focus();
        });
    }

    // Вызовем фильтрацию сразу при загрузке страницы
    filterProducts();
});
