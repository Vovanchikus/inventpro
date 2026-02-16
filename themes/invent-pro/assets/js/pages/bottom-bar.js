/**
 * bottom-bar.js
 * ----------
 * Скрипты для страницы склада / истории операций
 */

document.addEventListener("DOMContentLoaded", () => {
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
    // 3. DOM элементы (БЕЗОПАСНО)
    // ============================================================
    const bottomBar = document.getElementById("bottomBar");
    const countEl = document.getElementById("bottomBarCount");

    const categoryMenu = document.getElementById("categoryMenu");
    const categoryOverlay = document.querySelector(".category-menu__overlay");

    const createOperationToggle = document.getElementById(
        "createOperationDropdownToggle",
    );
    const createOperationMenu = document.getElementById(
        "createOperationDropdownMenu",
    );

    const hasCategoryMenu = !!categoryMenu;

    // ============================================================
    // 4. Сброс меню категорий (БЕЗОПАСНО)
    // ============================================================
    function resetCategoryMenu() {
        if (!hasCategoryMenu) return;

        categoryMenu
            .querySelectorAll(".category-menu__item.active")
            .forEach((el) => el.classList.remove("active"));

        categoryMenu
            .querySelectorAll(".category-menu__children")
            .forEach((el) => el.classList.add("hidden"));
    }

    // ============================================================
    // 5. Обновление нижней панели
    // ============================================================
    function updateBottomBar() {
        const selected = getSelectedFromCheckboxes();
        saveSelected(selected);

        if (!bottomBar || !countEl) return;

        if (selected.length > 0) {
            bottomBar.classList.remove("hidden");
            countEl.textContent = selected.length;
        } else {
            bottomBar.classList.add("hidden");
            countEl.textContent = "0";
            createOperationMenu?.classList.remove("show");
        }
    }

    document.querySelectorAll(".product-check").forEach((cb) => {
        cb.addEventListener("change", updateBottomBar);
    });

    // ============================================================
    // 6. Создание операции
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

    // Создать операцию без заметки (из dropdown)
    document
        .getElementById("createOperationDirect")
        ?.addEventListener("click", () => {
            const selected = getSelectedFromCheckboxes();
            if (!selected.length) {
                toast("Выберите хотя бы один товар!", "error");
                return;
            }

            localStorage.setItem("createOperation", JSON.stringify(selected));
            createOperationMenu?.classList.remove("show");
            window.location.href = "/add-operation";
        });

    // (createNote and addToNote are handled by notes-modal.js: it opens modals and reads localStorage)

    // ============================================================
    // 7. Редактирование операции
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
                "error",
            );
            return;
        }

        localStorage.setItem("editOperation", JSON.stringify(selected));
        window.location.href = "/edit-operation";
    });

    // ============================================================
    // 8. Открытие меню категорий
    // ============================================================
    document.getElementById("addToCategory")?.addEventListener("click", () => {
        if (!hasCategoryMenu) return;

        const selected = getSelectedFromCheckboxes();
        if (!selected.length) {
            toast("Товары не выбраны", "error");
            return;
        }

        resetCategoryMenu();
        categoryMenu.classList.remove("hidden");
        categoryOverlay?.classList.remove("hidden");
    });

    // ============================================================
    // 9. Назначение категории (ИСПРАВЛЕНО)
    // ============================================================
    if (hasCategoryMenu) {
        categoryMenu.addEventListener("click", (event) => {
            const clickedItem = event.target.closest(".category-menu__item");
            if (!clickedItem) return;

            const parentUl = clickedItem.parentElement;

            // ❗ ТОЛЬКО соседи текущего уровня
            parentUl
                .querySelectorAll(":scope > .category-menu__item")
                .forEach((el) => {
                    if (el !== clickedItem) {
                        el.classList.remove("active");

                        const children = el.querySelector(
                            ":scope > .category-menu__children",
                        );
                        if (children) {
                            children.classList.add("hidden");
                        }
                    }
                });

            // Активируем текущий
            clickedItem.classList.toggle("active");

            // Подкатегории
            const children = clickedItem.querySelector(
                ":scope > .category-menu__children",
            );

            if (children) {
                children.classList.toggle("hidden");
                return; // если есть дети — AJAX не делаем
            }

            // Листовая категория
            const selected = getSelectedFromCheckboxes();
            const productIds = selected.map((p) => p.product_id);

            if (!productIds.length) {
                toast("Нет выбранных товаров!", "error");
                return;
            }

            const categoryId = clickedItem.dataset.id;

            $.request("onAssignCategory", {
                data: {
                    product_ids: productIds,
                    category_id: categoryId,
                },
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
    }

    // ============================================================
    // 10. Закрытие нижней панели (РАБОТАЕТ ВЕЗДЕ)
    // ============================================================
    document.addEventListener("click", (e) => {
        const closeBtn = e.target.closest(".bottom-bar__close");
        if (!closeBtn) return;

        localStorage.removeItem("selectedProducts");

        document
            .querySelectorAll(".product-check")
            .forEach((cb) => (cb.checked = false));

        updateBottomBar();

        if (hasCategoryMenu) {
            categoryMenu.classList.add("hidden");
            resetCategoryMenu();
        }

        categoryOverlay?.classList.add("hidden");
        createOperationMenu?.classList.remove("show");
    });

    // ============================================================
    // 11. Закрытие меню при клике на overlay
    // ============================================================
    categoryOverlay?.addEventListener("click", () => {
        if (hasCategoryMenu) {
            categoryMenu.classList.add("hidden");
            resetCategoryMenu();
        }
        categoryOverlay.classList.add("hidden");
    });

    // ============================================================
    // 11. Dropdown "Создать операцию"
    // ============================================================
    createOperationToggle?.addEventListener("click", (e) => {
        e.preventDefault();
        createOperationMenu?.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!createOperationMenu || !createOperationToggle) return;
        if (!createOperationMenu.classList.contains("show")) return;

        const target = e.target;
        if (
            createOperationToggle.contains(target) ||
            createOperationMenu.contains(target)
        )
            return;

        createOperationMenu.classList.remove("show");
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" || e.key === "Esc") {
            createOperationMenu?.classList.remove("show");
        }
    });

    // Закрываем dropdown после выбора "С заметкой"
    document.getElementById("createNote")?.addEventListener("click", () => {
        createOperationMenu?.classList.remove("show");
    });

    // ============================================================
    // 12. Инициализация
    // ============================================================
    updateBottomBar();

    // ==============================================
    // Notes: create / add handlers
    // ==============================================
    function initModalForm(handler) {
        const modalForm = document.querySelector(
            `.modal .modal-content form[data-request="${handler}"]`,
        );
        if (!modalForm) return;

        // Отвязка старого обработчика, чтобы не дублировался
        modalForm.removeEventListener("submit", modalForm._handler);

        modalForm._handler = function (e) {
            e.preventDefault();
            $(modalForm).request(handler, {
                data: $(modalForm).serializeArray(),
                success(res) {
                    if (typeof handleServerResponse === "function")
                        handleServerResponse(res);

                    if (res && res.success) {
                        Modal.hide();
                        document
                            .querySelectorAll(".product-check")
                            .forEach((cb) => (cb.checked = false));
                        localStorage.removeItem("selectedProducts");
                        updateBottomBar();
                    }
                },
                error() {
                    toast("Ошибка сервера", "error");
                },
            });
        };

        modalForm.addEventListener("submit", modalForm._handler);
    }

    // Создать заметку
    document.getElementById("createNote")?.addEventListener("click", () => {
        const selected = getSelectedFromCheckboxes();
        if (!selected.length) {
            toast("Выберите товары", "error");
            return;
        }

        $.request("onShowCreateModal", {
            data: { selected_products: selected },
            success(resp) {
                if (resp && resp.modalContent) {
                    Modal.show(resp.modalContent, "info", "Создать заметку");
                    initModalForm("onCreateNote");
                }
            },
            error() {
                toast("Ошибка при запросе формы", "error");
            },
        });
    });

    // Добавить в существующую заметку
    document.getElementById("addToNote")?.addEventListener("click", () => {
        const selected = getSelectedFromCheckboxes();
        if (!selected.length) {
            toast("Выберите товары", "error");
            return;
        }

        $.request("onShowAddModal", {
            data: { selected_products: selected },
            success(resp) {
                if (resp && resp.modalContent) {
                    Modal.show(resp.modalContent, "info", "Добавить в заметку");
                    initModalForm("onAddToExistingNote");
                }
            },
            error() {
                toast("Ошибка при запросе формы", "error");
            },
        });
    });
});
