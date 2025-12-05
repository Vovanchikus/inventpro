/**
 * bottom-bar.js
 * ----------
 * Скрипты для страницы склада.
 *
 * Функции:
 * - Управление выбором товаров через чекбоксы
 * - Подсчет выбранных товаров и отображение нижней панели
 * - Сохранение выбранных товаров в localStorage
 * - Кнопка "Создать операцию"
 * - Кнопка "Редактировать операцию"
 * - Кнопка закрытия нижней панели
 */

document.addEventListener("DOMContentLoaded", () => {
    // Очищаем предыдущий выбор при загрузке страницы
    localStorage.removeItem("selectedProducts");

    /**
     * Получить текущий список выбранных товаров из localStorage
     * @returns {Array}
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
     * @returns {Array}
     */
    function getSelectedFromCheckboxes() {
        const selected = [];
        document.querySelectorAll(".product-check:checked").forEach((cb) => {
            selected.push({
                operation_id: parseInt(cb.dataset.operation_id),
                product_id: parseInt(cb.dataset.product_id),
                name: cb.dataset.name,
                inv_number: cb.dataset.invNumber,
                unit: cb.dataset.unit,
                price: cb.dataset.price,
                quantity: cb.dataset.quantity,
                sum: cb.dataset.sum,
                operation_id: cb.dataset.operationId,
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

        const bottomBar = document.getElementById("bottomBar");
        const countEl = document.getElementById("bottomBarCount");

        if (selected.length > 0) {
            bottomBar.classList.remove("hidden");
            countEl.textContent = `${selected.length}`;
        } else {
            bottomBar.classList.add("hidden");
            countEl.textContent = "0";
        }
    }

    // Привязываем событие к каждому чекбоксу
    document.querySelectorAll(".product-check").forEach((cb) => {
        cb.addEventListener("change", updateBottomBar);
    });

    /**
     * Кнопка "Создать операцию"
     */
    document
        .getElementById("createOperation")
        ?.addEventListener("click", () => {
            const selected = getSelectedFromCheckboxes();
            if (selected.length === 0) {
                alert("Выберите хотя бы один товар!");
                return;
            }

            // Сохраняем данные для создания операции
            localStorage.setItem("createOperation", JSON.stringify(selected));

            window.location.href = "/add-operation";
        });

    /**
     * Кнопка "Редактировать операцию" (интеграция 5 шага)
     */
    document.getElementById("editOperation")?.addEventListener("click", () => {
        const selected = getSelectedFromCheckboxes();

        if (selected.length === 0) {
            alert("Выберите хотя бы один товар для редактирования!");
            return;
        }

        // Проверяем, что все выбранные товары принадлежат одной операции
        const operationIds = [...new Set(selected.map((p) => p.operation_id))];
        if (operationIds.length > 1) {
            alert("Вы можете редактировать товары только из одной операции!");
            return;
        }

        // Сохраняем выбранные товары для страницы редактирования
        localStorage.setItem("editOperation", JSON.stringify(selected));

        // Переход на страницу редактирования операции
        // Если нужно передавать ID операции, его можно добавить в URL
        window.location.href = "/edit-operation";
    });

    /**
     * Кнопка закрытия нижней панели
     */
    const bottomBarCloseBtn = document.querySelector(".bottom-bar__close");
    bottomBarCloseBtn?.addEventListener("click", () => {
        // Очистка localStorage
        localStorage.removeItem("selectedProducts");

        // Сброс чекбоксов
        document.querySelectorAll(".product-check").forEach((cb) => {
            cb.checked = false;
        });

        // Обновление панели
        updateBottomBar();
    });

    // Обновляем панель при загрузке страницы
    updateBottomBar();
});
