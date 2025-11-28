/**
 * add-operation.js
 * ----------
 * Скрипты для страницы добавления операции
 *
 * Функции:
 * - updateProductButtons() — управление видимостью кнопок "Добавить продукт / Добавить из БД"
 * - attachRowEvents() — добавление/удаление строк документов и продуктов
 * - attachStockHint() — подсказка остатка на складе при фокусе на поле количества
 * - attachCalcEvents() — автоподсчет суммы (quantity * price)
 * - formInputsObserver() — автоснятие ошибок при вводе
 * - initProductSearchModal() — инициализация модалки поиска товаров
 * - addProductToOperation() — добавление выбранного товара в форму операции
 */

document.addEventListener("DOMContentLoaded", () => {
    const addProductBtn = document.getElementById("add-product");
    const addFromDBBtn = document.getElementById("btnSearchProduct");
    const productsWrapper = document.getElementById(
        "add-operation__products-wrapper"
    );
    const documentsWrapper = document.getElementById(
        "add-operation__documents-wrapper"
    );
    const removeDocumentBtn = document.getElementById("remove-document");
    const removeProductBtn = document.getElementById("remove-product");

    // =============================
    // Автоснятие ошибок при вводе
    // =============================
    const formInputsObserver = () => {
        document
            .querySelectorAll("#addOperationForm input")
            .forEach((input) => {
                input.removeEventListener("input", input._removeMessage);
                input._removeMessage = () => {
                    input.classList.remove("error-field");
                    const errDiv = input
                        .closest(".form-floating")
                        ?.querySelector(".field-message.field-message--error");
                    if (errDiv) errDiv.remove();
                };
                input.addEventListener("input", input._removeMessage);
            });
    };
    formInputsObserver();

    // =============================
    // Показывает кнопку "Добавить продукт" или "Добавить из БД"
    // =============================
    function updateProductButtons() {
        const radios = document.querySelectorAll('input[name="type_id"]');
        let selectedType = null;
        radios.forEach((r) => {
            if (r.checked)
                selectedType = r.nextElementSibling.textContent
                    .trim()
                    .toLowerCase();
        });

        if (!selectedType) {
            addProductBtn?.classList.remove("d-none");
            addFromDBBtn?.classList.add("d-none");
            return;
        }

        if (["передача", "списание"].includes(selectedType)) {
            addProductBtn?.classList.add("d-none");
            addFromDBBtn?.classList.remove("d-none");
        } else {
            addProductBtn?.classList.remove("d-none");
            addFromDBBtn?.classList.add("d-none");
        }
    }

    document
        .querySelectorAll('input[name="type_id"]')
        .forEach((r) => r.addEventListener("change", updateProductButtons));
    updateProductButtons();

    // =============================
    // Добавление/удаление строк документов или продуктов
    // =============================
    function attachRowEvents(wrapper, addBtn, removeBtn, rowSelector) {
        addBtn?.addEventListener("click", () => {
            const first = wrapper.querySelector(rowSelector);
            const clone = first.cloneNode(true);

            clone.querySelectorAll("input").forEach((i) => (i.value = ""));
            clone.querySelectorAll(".field-message").forEach((m) => m.remove());
            clone
                .querySelectorAll(".error-field")
                .forEach((el) => el.classList.remove("error-field"));

            wrapper.appendChild(clone);
            formInputsObserver();

            if (rowSelector === ".add-operation__row--product") {
                attachStockHint(clone);
                attachCalcEvents(clone);
            }
        });

        removeBtn?.addEventListener("click", () => {
            const rows = wrapper.querySelectorAll(rowSelector);
            if (rows.length > 1) rows[rows.length - 1].remove();
        });
    }

    // =============================
    // Подсказка остатка на складе при фокусе на поле количества
    // =============================
    function attachStockHint(row) {
        const qtyInput = row.querySelector('input[name="quantity[]"]');
        const invInput = row.querySelector('input[name="inv_number[]"]');
        if (!qtyInput || !invInput) return;

        const parent =
            qtyInput.closest(".form-floating") || qtyInput.parentNode;
        let msgDiv = parent.querySelector(".field-message.field-message--info");
        if (!msgDiv) {
            msgDiv = document.createElement("div");
            msgDiv.className = "field-message field-message--info";
            msgDiv.style.display = "none";
            parent.appendChild(msgDiv);
        } else msgDiv.style.display = "none";

        const showStock = () => {
            const invNumber = invInput.value?.trim();
            if (!invNumber) return;

            $.request("onSearchProducts", {
                data: { query: invNumber },
                success: (data) => {
                    const product = data.results?.find(
                        (p) => p.inv_number === invNumber
                    );
                    if (product) {
                        msgDiv.textContent = `На складе: ${product.calculated_quantity} ед.`;
                        msgDiv.style.display = "block";
                    } else {
                        msgDiv.style.display = "none";
                    }
                },
            });
        };

        qtyInput.addEventListener("focus", showStock);
        qtyInput.addEventListener("blur", () => {
            msgDiv.style.display = "none";
        });
        invInput.addEventListener("input", () => {
            if (document.activeElement === qtyInput) showStock();
        });
    }

    // =============================
    // Автоподсчет суммы (quantity * price)
    // =============================
    function attachCalcEvents(row) {
        const qtyInput = row.querySelector('input[name="quantity[]"]');
        const priceInput = row.querySelector('input[name="price[]"]');
        const sumInput = row.querySelector('input[name="sum[]"]');
        if (!qtyInput || !priceInput || !sumInput) return;

        const calc = () => {
            const type = document
                .querySelector('input[name="type_id"]:checked')
                ?.nextElementSibling?.textContent.trim()
                .toLowerCase();
            if (!["передача", "списание"].includes(type)) return;
            if (!qtyInput.value || !priceInput.value) return;
            const quantity = parseFloat(qtyInput.value.replace(",", "."));
            const price = parseFloat(priceInput.value.replace(",", "."));
            if (!isNaN(quantity) && !isNaN(price))
                sumInput.value = (quantity * price).toFixed(2);
        };

        qtyInput.addEventListener("input", calc);
        priceInput.addEventListener("input", calc);
    }

    function initAutoSumCalc() {
        productsWrapper
            .querySelectorAll(".add-operation__row--product")
            .forEach(attachCalcEvents);
    }

    document
        .querySelectorAll('input[name="type_id"]')
        .forEach((r) =>
            r.addEventListener("change", () => setTimeout(initAutoSumCalc, 50))
        );
    addProductBtn?.addEventListener("click", () =>
        setTimeout(initAutoSumCalc, 50)
    );
    initAutoSumCalc();

    // =============================
    // Открытие модалки "Добавить из БД"
    // =============================
    addFromDBBtn?.addEventListener("click", () => {
        $.request("onShowProductSearchModal", {
            success: (data) => {
                // Показываем модалку с HTML из partial
                Modal.show(data.modalContent || "", "info", "Выбор товаров");

                // Инициализация модалки
                setTimeout(() => initProductSearchModal(), 50);
            },
            error: () => {
                alert("Ошибка загрузки модалки с товарами");
            },
        });
    });

    // =============================
    // Инициализация модалки поиска товаров
    // =============================
    function initProductSearchModal() {
        const modal = document.getElementById("modal-container");
        if (!modal) return;

        const searchInput = modal.querySelector("#searchProductInput");
        const searchBtn = modal.querySelector("#btnSearchProductModal");
        const resultsWrapper = modal.querySelector("#productSearchResults");

        if (!searchInput || !searchBtn || !resultsWrapper) return;
        resultsWrapper.innerHTML = "";

        let addSelectedBtn = modal.querySelector("#btnAddSelectedProducts");
        if (!addSelectedBtn) {
            addSelectedBtn = document.createElement("button");
            addSelectedBtn.id = "btnAddSelectedProducts";
            addSelectedBtn.className = "button button--md button--primary mt-2";
            addSelectedBtn.textContent = "Добавить выбранные";
            resultsWrapper.parentNode.appendChild(addSelectedBtn);
        }

        // Отвязываем старые события
        searchBtn.replaceWith(searchBtn.cloneNode(true));
        addSelectedBtn.replaceWith(addSelectedBtn.cloneNode(true));

        const newSearchBtn = modal.querySelector("#btnSearchProductModal");
        const newAddBtn = modal.querySelector("#btnAddSelectedProducts");

        function renderProductResult(product) {
            const div = document.createElement("div");
            div.className =
                "search-result-item d-flex align-items-center gap-2";

            const checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.dataset.name = product.name;
            checkbox.dataset.invNumber = product.inv_number;
            checkbox.dataset.unit = product.unit;
            checkbox.dataset.price = product.price;
            checkbox.dataset.calculatedQuantity = product.calculated_quantity;

            if (isProductAlreadyAdded(product.inv_number)) {
                div.style.backgroundColor = "#d3f8d3";
                checkbox.disabled = true;
            }

            const label = document.createElement("span");
            label.textContent = `${product.name} | ${product.inv_number} | Остаток: ${product.calculated_quantity}`;

            div.appendChild(checkbox);
            div.appendChild(label);
            resultsWrapper.appendChild(div);
        }

        function isProductAlreadyAdded(invNumber) {
            return Array.from(
                productsWrapper.querySelectorAll('input[name="inv_number[]"]')
            ).some((input) => input.value === invNumber);
        }

        const performSearch = () => {
            const query = searchInput.value.trim();
            if (!query) return;

            resultsWrapper.innerHTML = "<div>Идёт поиск...</div>";

            $.request("onSearchProducts", {
                data: { query },
                success: (data) => {
                    resultsWrapper.innerHTML = "";
                    if (data.results && data.results.length) {
                        data.results.forEach(renderProductResult);
                    } else {
                        resultsWrapper.innerHTML =
                            "<div>Ничего не найдено</div>";
                    }
                },
                error: () => {
                    resultsWrapper.innerHTML = "<div>Ошибка при поиске</div>";
                },
            });
        };

        newSearchBtn.addEventListener("click", performSearch);
        searchInput.addEventListener("keyup", (e) => {
            if (e.key === "Enter") performSearch();
        });

        newAddBtn.addEventListener("click", () => {
            const selectedProducts = [];
            resultsWrapper
                .querySelectorAll("input[type=checkbox]:checked")
                .forEach((cb) => {
                    selectedProducts.push({
                        name: cb.dataset.name,
                        inv_number: cb.dataset.invNumber,
                        unit: cb.dataset.unit,
                        price: cb.dataset.price,
                        calculated_quantity: cb.dataset.calculatedQuantity,
                    });
                });

            selectedProducts.forEach((product) => {
                if (!isProductAlreadyAdded(product.inv_number))
                    addProductToOperation(product);
            });

            Modal.hide();
        });
    }

    // =============================
    // Добавление товара в операцию
    // =============================
    function addProductToOperation(product) {
        let emptyRow = Array.from(
            productsWrapper.querySelectorAll(".add-operation__row--product")
        ).find((row) => row.querySelector('input[name="name[]"]').value === "");

        if (!emptyRow) {
            const firstRow = productsWrapper.querySelector(
                ".add-operation__row--product"
            );
            emptyRow = firstRow.cloneNode(true);
            emptyRow.querySelectorAll("input").forEach((i) => (i.value = ""));
            productsWrapper.appendChild(emptyRow);
        }

        emptyRow.querySelector('input[name="name[]"]').value =
            product.name || "";
        emptyRow.querySelector('input[name="inv_number[]"]').value =
            product.inv_number || "";
        emptyRow.querySelector('input[name="unit[]"]').value =
            product.unit || "";
        emptyRow.querySelector('input[name="price[]"]').value =
            product.price || "";
        emptyRow.querySelector('input[name="quantity[]"]').value = "";
        emptyRow.querySelector('input[name="sum[]"]').value = "";

        attachStockHint(emptyRow);
        attachCalcEvents(emptyRow);
    }

    // =============================
    // Привязка кнопок добавления/удаления
    // =============================
    attachRowEvents(
        productsWrapper,
        addProductBtn,
        removeProductBtn,
        ".add-operation__row--product"
    );
    attachRowEvents(
        documentsWrapper,
        document.getElementById("add-document"),
        removeDocumentBtn,
        ".add-operation__row--document"
    );

    // =============================
    // Ajax формы
    // =============================
    $(document).on(
        "ajaxSuccess",
        "#addOperationForm",
        function (event, context, data) {
            handleServerResponse(data);
        }
    );
});
