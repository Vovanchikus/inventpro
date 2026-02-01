document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("warehouse-search");
    const clearSearch = document.getElementById("clearSearch");
    const productList = document.getElementById("product-list");

    if (!searchInput || !productList) return;

    // Берём все элементы внутри product-list
    const items = Array.from(
        productList.querySelectorAll(
            ".warehouse__item, .operation-history__item",
        ),
    );

    const filterItems = () => {
        const query = searchInput.value.toLowerCase().trim();

        items.forEach((item) => {
            // Игнорируем строки без данных
            if (
                !item.querySelector(".operation-history__name") &&
                !item.querySelector(".warehouse__name")
            ) {
                item.style.display = "none";
                return;
            }

            let text = "";

            // Для склада
            const warehouseName =
                item.querySelector(".warehouse__name")?.textContent || "";
            const warehouseNumber =
                item.querySelector(".warehouse__number")?.textContent || "";
            if (warehouseName || warehouseNumber) {
                text = (warehouseName + " " + warehouseNumber).toLowerCase();
            }

            // Для истории операций
            const historyName =
                item.querySelector(".operation-history__name")?.textContent ||
                "";
            const historyInv =
                item.querySelector(".operation-history__number")?.textContent ||
                "";
            const historyCounteragent =
                item.querySelector(".operation-history__counteragent")
                    ?.textContent || "";
            if (historyName || historyInv || historyCounteragent) {
                text = (
                    historyName +
                    " " +
                    historyInv +
                    " " +
                    historyCounteragent
                ).toLowerCase();
            }

            item.style.display = text.includes(query) ? "" : "none";
        });

        if (clearSearch) {
            clearSearch.style.display = query ? "flex" : "none";
        }
    };

    searchInput.addEventListener("input", filterItems);

    if (clearSearch) {
        clearSearch.addEventListener("click", () => {
            searchInput.value = "";
            filterItems();
            searchInput.focus();
        });
    }

    filterItems(); // фильтруем сразу при загрузке
});
