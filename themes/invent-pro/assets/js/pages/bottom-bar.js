/**
 * bottom-bar.js
 * ----------
 * Скрипты для страницы склада.
 *
 * Основные функции:
 * - Управление выбором товаров через чекбоксы
 * - Подсчет количества выбранных товаров и отображение нижней панели
 * - Хранение выбора в localStorage
 * - Создание / редактирование операций
 * - Назначение категории через модальное меню
 * - Блокировка родительских категорий
 * - Корректное открытие и закрытие меню категорий
 * - Сброс активных элементов и подкатегорий при закрытии меню
 */

document.addEventListener("DOMContentLoaded", () => {
    // ============================================================
    // 0. Содержание
    // ============================================================
    // 1. Очистка предыдущего выбора
    // 2. Утилиты
    // 3. Обновление нижней панели
    // 4. Создание операции
    // 5. Редактирование операции
    // 6. Открытие меню категорий
    // 7. Назначение категории
    // 8. Закрытие нижней панели
    // 9. Закрытие меню при клике на overlay
    // 10. Инициализация

    // ============================================================
    // 1. Очистка предыдущего выбора
    // ============================================================
    localStorage.removeItem("selectedProducts");

    // ============================================================
    // 2. Утилиты
    // ============================================================
    function getSelected() {
        return JSON.parse(localStorage.getItem("selectedProducts") || "[]");
    }

    function saveSelected(list) {
        localStorage.setItem("selectedProducts", JSON.stringify(list));
    }

    function getSelectedFromCheckboxes() {
        const selected = [];
        document.querySelectorAll(".product-check:checked").forEach((cb) => {
            selected.push({
                product_id: parseInt(cb.dataset.id),
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

    // ============================================================
    // Вспомогательная функция: сброс всех активных категорий и скрытие подкатегорий
    // ============================================================
    function resetCategoryMenu() {
        categoryMenu
            .querySelectorAll(".category-menu__item.active")
            .forEach((el) => el.classList.remove("active"));

        categoryMenu
            .querySelectorAll(".category-menu__children")
            .forEach((el) => el.classList.add("hidden"));
    }

    // ============================================================
    // 3. Обновление нижней панели
    // ============================================================
    function updateBottomBar() {
        const selected = getSelectedFromCheckboxes();
        saveSelected(selected);

        const bottomBar = document.getElementById("bottomBar");
        const countEl = document.getElementById("bottomBarCount");

        if (selected.length > 0) {
            bottomBar.classList.remove("hidden");
            countEl.textContent = selected.length;
        } else {
            bottomBar.classList.add("hidden");
            countEl.textContent = "0";
        }
    }

    document.querySelectorAll(".product-check").forEach((cb) => {
        cb.addEventListener("change", updateBottomBar);
    });

    // ============================================================
    // 4. Создание операции
    // ============================================================
    document
        .getElementById("createOperation")
        ?.addEventListener("click", () => {
            const selected = getSelectedFromCheckboxes();
            if (!selected.length) {
                toast("Выберите хотя бы один товар!", "error");
                return;
            }
            localStorage.setItem("createOperation", JSON.stringify(selected));
            window.location.href = "/add-operation";
        });

    // ============================================================
    // 5. Редактирование операции
    // ============================================================
    document.getElementById("editOperation")?.addEventListener("click", () => {
        const selected = getSelectedFromCheckboxes();
        if (!selected.length) {
            toast("Выберите хотя бы один товар!", "error");
            return;
        }

        const operationIds = [...new Set(selected.map((p) => p.operation_id))];
        if (operationIds.length > 1) {
            toast(
                "Можно редактировать только товары из одной операции!",
                "error"
            );
            return;
        }

        localStorage.setItem("editOperation", JSON.stringify(selected));
        window.location.href = "/edit-operation";
    });

    // ============================================================
    // 6. Открытие меню категорий
    // ============================================================
    const categoryMenu = document.getElementById("categoryMenu");
    const categoryOverlay = document.querySelector(".category-menu__overlay");

    document.getElementById("addToCategory")?.addEventListener("click", () => {
        const selected = getSelectedFromCheckboxes();
        if (!selected.length) {
            toast("Товары не выбраны", "error");
            return;
        }

        // Сброс меню перед открытием
        resetCategoryMenu();

        categoryMenu.classList.remove("hidden");
        categoryOverlay?.classList.remove("hidden");
    });

    // ============================================================
    // 7. Назначение категории с раскрытием родительских категорий
    // ============================================================
    categoryMenu.addEventListener("click", (event) => {
        const clickedItem = event.target.closest(".category-menu__item");
        if (!clickedItem) return;

        const parentUl = clickedItem.parentElement;

        // Закрываем всех соседей на этом уровне вместе с их подкатегориями
        parentUl.querySelectorAll(".category-menu__item").forEach((el) => {
            if (el !== clickedItem) {
                el.classList.remove("active");
                el.querySelectorAll(".category-menu__children").forEach(
                    (child) => {
                        child.classList.add("hidden");
                    }
                );
            }
        });

        // Переключаем active на текущем элементе
        clickedItem.classList.toggle("active");

        // Раскрытие подкатегорий текущего элемента (если есть)
        const children = clickedItem.querySelector(".category-menu__children");
        if (children) {
            children.classList.toggle("hidden");
            return; // если есть подкатегории, AJAX не выполняем
        }

        // Листовая категория (AJAX)
        const selected = getSelectedFromCheckboxes();
        const productIds = selected.map((p) => p.product_id);

        if (!productIds.length) {
            toast("Нет выбранных товаров!", "error");
            return;
        }

        const categoryId = clickedItem.dataset.id;

        $.request("onAssignCategory", {
            data: { product_ids: productIds, category_id: categoryId },
            success(res) {
                handleServerResponse(res);

                document
                    .querySelectorAll(".product-check")
                    .forEach((cb) => (cb.checked = false));
                localStorage.removeItem("selectedProducts");
                updateBottomBar();

                categoryMenu.classList.add("hidden");
                categoryOverlay?.classList.add("hidden");
                resetCategoryMenu();
            },
            error() {
                toast("Ошибка при назначении категории", "error");
            },
        });
    });

    // ============================================================
    // 8. Закрытие нижней панели
    // ============================================================
    document
        .querySelector(".bottom-bar__close")
        ?.addEventListener("click", () => {
            localStorage.removeItem("selectedProducts");
            document
                .querySelectorAll(".product-check")
                .forEach((cb) => (cb.checked = false));
            updateBottomBar();

            categoryMenu.classList.add("hidden");
            categoryOverlay?.classList.add("hidden");
            resetCategoryMenu();
        });

    // ============================================================
    // 9. Закрытие меню категорий при клике на overlay
    // ============================================================
    categoryOverlay?.addEventListener("click", () => {
        categoryMenu.classList.add("hidden");
        categoryOverlay.classList.add("hidden");
        resetCategoryMenu();
    });

    // ============================================================
    // 10. Инициализация
    // ============================================================
    updateBottomBar();
});
