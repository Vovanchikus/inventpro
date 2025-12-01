// warehouse.js
document.addEventListener("DOMContentLoaded", () => {
    // Очищаем предыдущий выбор при загрузке страницы
    localStorage.removeItem("selectedProducts");

    /**
     * Получить текущий список выбранных товаров из localStorage
     */
    function getSelected() {
        return JSON.parse(localStorage.getItem("selectedProducts") || "[]");
    }

    /**
     * Сохранить список выбранных товаров в localStorage
     * @param {Array} list
     */
    function saveSelected(list) {
        localStorage.setItem("selectedProducts", JSON.stringify(list));
    }

    /**
     * Формируем массив выбранных товаров на основе чекбоксов на странице
     */
    function getSelectedFromCheckboxes() {
        const selected = [];
        document.querySelectorAll(".product-check:checked").forEach((cb) => {
            selected.push({
                id: parseInt(cb.dataset.id),
                name: cb.dataset.name,
                inv_number: cb.dataset.invNumber,
                unit: cb.dataset.unit,
                price: cb.dataset.price,
            });
        });
        return selected;
    }

    /**
     * Обновление нижней панели с количеством выбранных товаров
     */
    function updateBottomBar() {
        const selected = getSelectedFromCheckboxes();
        saveSelected(selected);

        const bar = document.getElementById("bottomBar");
        const countEl = document.getElementById("bottomBarCount");

        if (selected.length > 0) {
            bar.classList.remove("hidden");
            countEl.textContent = `${selected.length}`;
        } else {
            bar.classList.add("hidden");
            countEl.textContent = "0";
        }
    }

    // Привязываем событие к каждому чекбоксу
    document.querySelectorAll(".product-check").forEach((cb) => {
        cb.addEventListener("change", updateBottomBar);
    });

    /**
     * Кнопка "Создать операцию"
     * - Сохраняет все выбранные товары во временный localStorage
     * - Переходит на страницу создания операции
     */
    document
        .getElementById("createOperation")
        ?.addEventListener("click", () => {
            const selected = getSelectedFromCheckboxes();
            if (selected.length === 0) {
                alert("Выберите хотя бы один товар!");
                return;
            }

            // Сохраняем все данные выбранных товаров
            localStorage.setItem("operationProducts", JSON.stringify(selected));

            // Переходим на страницу создания операции
            window.location.href = "/add-operation";
        });

    // Обновляем панель при загрузке страницы
    updateBottomBar();
});
