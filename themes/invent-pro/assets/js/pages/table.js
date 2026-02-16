document.addEventListener("DOMContentLoaded", () => {
    if (window.location.pathname.startsWith("/warehouse/")) {
        // Страница warehouse/:slug — не выполняем скрипт
        return;
    }

    const header = document.querySelector(".main-box");
    const selectAll = document.getElementById("warehouse-select-all");
    const productChecks = document.querySelectorAll(".product-check");

    if (!header) return;

    const getVisibleChecks = () =>
        Array.from(productChecks).filter((checkbox) => {
            const row = checkbox.closest(".warehouse__item");
            if (!row) return false;
            return row.offsetParent !== null;
        });

    const syncSelectAllState = () => {
        if (!selectAll) return;

        const visibleChecks = getVisibleChecks();
        if (!visibleChecks.length) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
            return;
        }

        const allChecked = visibleChecks.every((item) => item.checked);
        const anyChecked = visibleChecks.some((item) => item.checked);

        selectAll.checked = allChecked;
        selectAll.indeterminate = !allChecked && anyChecked;
    };

    if (selectAll) {
        selectAll.addEventListener("change", (event) => {
            const isChecked = event.target.checked;

            getVisibleChecks().forEach((checkbox) => {
                checkbox.checked = isChecked;
                checkbox.dispatchEvent(new Event("change", { bubbles: true }));
            });
        });

        productChecks.forEach((checkbox) => {
            checkbox.addEventListener("change", syncSelectAllState);
        });
    }

    syncSelectAllState();

    // Слушаем скролл
    window.addEventListener("scroll", () => {
        const rect = header.getBoundingClientRect();

        if (rect.top <= 0) {
            // Шапка достигла верха окна — добавляем класс
            header.classList.add("scrolled");
        } else {
            // Шапка вернулась вниз — убираем класс
            header.classList.remove("scrolled");
        }
    });
});
