/**
 * operation-form.js
 * -----------------
 * Универсальные скрипты для страницы добавления и редактирования операции
 *
 * Функции:
 * - updateProductButtons() — управление видимостью кнопок "Добавить продукт / Добавить из БД"
 * - attachRowEvents() — добавление/удаление строк документов и продуктов
 * - attachStockHint() — подсказка остатка на складе при фокусе на поле количества
 * - attachCalcEvents() — автоподсчет суммы (quantity * price)
 * - formInputsObserver() — автоснятие ошибок при вводе
 * - addProductToOperation() — добавление выбранного товара в форму операции
 * - handleRemoveButton() — удаление строки через кнопку рядом с ней
 * - setupFileUpload() — обработка кнопок загрузки PDF для каждой строки
 */

document.addEventListener("DOMContentLoaded", () => {
    // ==============================
    // Определяем текущую форму (add или edit)
    // ==============================
    const form = document.querySelector(
        "#addOperationForm, #editOperationForm"
    );
    if (!form) return;

    const isEdit = form.id === "editOperationForm";

    // ==============================
    // Основные элементы формы
    // ==============================
    const addProductBtn = document.getElementById("add-product");
    const addFromDBBtn = document.getElementById("btnSearchProduct");
    const productsWrapper = document.getElementById(
        "operation-form__products-wrapper"
    );
    const documentsWrapper = document.getElementById(
        "operation-form__documents-wrapper"
    );

    // ==============================
    // Автоснятие ошибок при вводе
    // ==============================
    const formInputsObserver = () => {
        form.querySelectorAll("input").forEach((input) => {
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

    // ==============================
    // Управление видимостью кнопок "Добавить продукт / Добавить из БД"
    // ==============================
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

    // ==============================
    // Управление кнопками удаления строк
    // ==============================
    function updateRemoveButtons(wrapper, rowSelector, btnSelector) {
        const rows = wrapper.querySelectorAll(rowSelector);
        rows.forEach((row, i) => {
            const btn = row.querySelector(btnSelector);
            if (!btn) return;
            btn.style.display = i === 0 ? "none" : "flex"; // первая строка без кнопки удаления
        });
    }

    function deleteRow(row, wrapper, rowSelector, btnSelector) {
        const rows = Array.from(wrapper.querySelectorAll(rowSelector));
        const index = rows.indexOf(row);
        if (index > 0) {
            row.remove();
        } else {
            console.warn("Нельзя удалить первую строку");
        }
        updateRemoveButtons(wrapper, rowSelector, btnSelector);
    }

    function handleRemoveButton(wrapper, rowSelector, btnSelector) {
        wrapper.addEventListener("click", (e) => {
            const btn = e.target.closest(btnSelector);
            if (!btn) return;
            const row = btn.closest(rowSelector);
            if (!row) return;
            deleteRow(row, wrapper, rowSelector, btnSelector);
        });
        updateRemoveButtons(wrapper, rowSelector, btnSelector);
    }

    handleRemoveButton(
        documentsWrapper,
        ".operation-form__row--document",
        ".remove-document-btn"
    );
    handleRemoveButton(
        productsWrapper,
        ".operation-form__row--product",
        ".remove-product-btn"
    );

    // ==============================
    // Работа с загрузкой файлов (PDF) для каждой строки
    // ==============================
    function setupFileUpload(row) {
        const btn = row.querySelector(".doc-upload-btn");
        const input = row.querySelector(".doc-file-input");
        if (!btn || !input) return;

        btn.onclick = null;
        input.onchange = null;

        btn.addEventListener("click", () => input.click());
        input.addEventListener("change", () => {
            if (input.files[0]) {
                btn.innerHTML = `
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM16.78 9.7L11.11 15.37C10.97 15.51 10.78 15.59 10.58 15.59C10.38 15.59 10.19 15.51 10.05 15.37L7.22 12.54C6.93 12.25 6.93 11.77 7.22 11.48C7.51 11.19 7.99 11.19 8.28 11.48L10.58 13.78L15.72 8.64C16.01 8.35 16.49 8.35 16.78 8.64C17.07 8.93 17.07 9.4 16.78 9.7Z" fill="currentColor"/>
                    </svg>
                    ${input.files[0].name}`;
            } else {
                btn.innerHTML = `
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.70711 9.29289L11 13.5858V3C11 2.44772 11.4477 2 12 2C12.5523 2 13 2.44772 13 3V13.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.5196 16.8946 12.2652 17 12 17C11.7348 17 11.4804 16.8946 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289Z" fill="currentColor"/>
                        <path d="M21 20C21.5523 20 22 20.4477 22 21C22 21.5523 21.5523 22 21 22H3C2.44772 22 2 21.5523 2 21C2 20.4477 2.44772 20 3 20H21Z" fill="currentColor"/>
                    </svg>
                    Загрузить PDF`;
            }
        });
    }

    documentsWrapper
        .querySelectorAll(".operation-form__row--document")
        .forEach(setupFileUpload);

    // ==============================
    // Добавление/клонирование строк документов или продуктов
    // ==============================
    function attachRowEvents(wrapper, addBtn, rowSelector) {
        addBtn?.addEventListener("click", () => {
            const first = wrapper.querySelector(rowSelector);
            const clone = first.cloneNode(true);

            clone.querySelectorAll("input").forEach((i) => {
                if (i.type !== "file") i.value = "";
            });
            clone.querySelectorAll(".field-message").forEach((m) => m.remove());
            clone
                .querySelectorAll(".error-field")
                .forEach((el) => el.classList.remove("error-field"));

            // Работа с кнопкой загрузки PDF
            if (rowSelector.includes("document")) {
                const oldButton = clone.querySelector(".doc-upload-btn");
                const oldInput = clone.querySelector(".doc-file-input");

                if (oldButton) oldButton.remove();
                if (oldInput) oldInput.remove();

                const newInput = document.createElement("input");
                newInput.type = "file";
                newInput.name = "doc_file[]";
                newInput.className = "doc-file-input";
                newInput.style.display = "none";

                const newButton = document.createElement("button");
                newButton.type = "button";
                newButton.className = oldButton.className;
                newButton.innerHTML = `
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.70711 9.29289L11 13.5858V3C11 2.44772 11.4477 2 12 2C12.5523 2 13 2.44772 13 3V13.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.5196 16.8946 12.2652 17 12 17C11.7348 17 11.4804 16.8946 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289Z" fill="currentColor"/>
                        <path d="M21 20C21.5523 20 22 20.4477 22 21C22 21.5523 21.5523 22 21 22H3C2.44772 22 2 21.5523 2 21C2 20.4477 2.44772 20 3 20H21Z" fill="currentColor"/>
                    </svg>
                    Загрузить PDF`;

                const btnBox =
                    clone.querySelector(".operation-form__button-box") || clone;
                btnBox.appendChild(newButton);
                btnBox.appendChild(newInput);

                setupFileUpload(clone);
            }

            wrapper.appendChild(clone);

            // Обновляем кнопки удаления
            updateRemoveButtons(
                wrapper,
                rowSelector,
                rowSelector.includes("product")
                    ? ".remove-product-btn"
                    : ".remove-document-btn"
            );

            formInputsObserver();

            if (rowSelector.includes("product")) {
                attachStockHint(clone);
                attachCalcEvents(clone);
                attachInvAutocomplete(clone);
            }
        });
    }

    attachRowEvents(
        productsWrapper,
        addProductBtn,
        ".operation-form__row--product"
    );
    attachRowEvents(
        documentsWrapper,
        document.getElementById("add-document"),
        ".operation-form__row--document"
    );

    // ==============================
    // Подсказка остатка на складе
    // ==============================
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
        } else {
            msgDiv.style.display = "none";
        }

        const showStock = () => {
            const invNumber = invInput.value?.trim();
            if (!invNumber) {
                msgDiv.style.display = "none";
                return;
            }

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

        // Слушатели
        qtyInput.addEventListener("focus", showStock);
        qtyInput.addEventListener("blur", () => {
            msgDiv.style.display = "none";
        });

        invInput.addEventListener("input", () => {
            if (document.activeElement === qtyInput) showStock();
        });

        // Сохраняем функцию для вызова после выбора из подсказки
        row._showStock = showStock;
    }

    // ==============================
    // Автоподсчет суммы (quantity * price)
    // ==============================
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
            .querySelectorAll(".operation-form__row--product")
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

    // ==============================
    // Добавление товара в операцию
    // ==============================
    function addProductToOperation(product) {
        let emptyRow = Array.from(
            productsWrapper.querySelectorAll(".operation-form__row--product")
        ).find((row) => row.querySelector('input[name="name[]"]').value === "");

        if (!emptyRow) {
            const firstRow = productsWrapper.querySelector(
                ".operation-form__row--product"
            );
            emptyRow = firstRow.cloneNode(true);
            emptyRow.querySelectorAll("input").forEach((i) => (i.value = ""));
            productsWrapper.appendChild(emptyRow);
        }

        emptyRow.querySelector('input[name="name[]"]').value =
            product.name || "";
        emptyRow.querySelector('input[name="inv_number[]"]').value =
            product.inv_number || product.id || "";
        emptyRow.querySelector('input[name="unit[]"]').value =
            product.unit || "";
        emptyRow.querySelector('input[name="price[]"]').value =
            product.price || "";
        emptyRow.querySelector('input[name="quantity[]"]').value =
            product.quantity || "";
        emptyRow.querySelector('input[name="sum[]"]').value = product.sum || "";

        attachStockHint(emptyRow);
        attachCalcEvents(emptyRow);

        // Настройка PDF для новой строки, если есть
        setupFileUpload(emptyRow);
    }

    // ==============================
    // Подтягиваем товары из localStorage
    // ==============================
    const storageKey = isEdit ? "editOperation" : "createOperation";
    const storedProducts = JSON.parse(localStorage.getItem(storageKey) || "[]");
    if (storedProducts.length) {
        storedProducts.forEach(addProductToOperation);
        localStorage.removeItem(storageKey);
    }

    // ==============================
    // По номенклатурному номеру проверяем есть ли такой товар на складе на странице добавлении и редактировании операции
    // ==============================
    function attachInvAutocomplete(row) {
        const invInput = row.querySelector('input[name="inv_number[]"]');

        // создаём подсказку только для этой строки
        let suggestBox = row.querySelector(
            ".operation-form__input--inv-suggestion"
        );
        if (!suggestBox) {
            suggestBox = document.createElement("div");
            suggestBox.className = "operation-form__input--inv-suggestion";
            suggestBox.style.position = "absolute";
            suggestBox.style.zIndex = 1000;
            suggestBox.style.display = "none";
            row.appendChild(suggestBox);
        }

        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        invInput.addEventListener(
            "input",
            debounce(async () => {
                const value = invInput.value.trim();
                suggestBox.innerHTML = "";
                if (!value) {
                    suggestBox.style.display = "none";
                    return;
                }

                const result = await $.request("onSearchProductsByInv", {
                    data: { query: value },
                });

                const products = result.products || [];
                const matches = products.filter((p) =>
                    p.inv_number.startsWith(value)
                );

                if (!matches.length) {
                    suggestBox.style.display = "none";
                    return;
                }

                // создаём элементы подсказки
                suggestBox.innerHTML = matches
                    .map(
                        (p) => `
                    <div class="inv-suggestion-item"
                        data-name="${p.name}"
                        data-unit="${p.unit}"
                        data-price="${p.price}"
                        data-inv="${p.inv_number}">
                        ${p.name} (${p.inv_number})
                    </div>
                `
                    )
                    .join("");

                suggestBox.style.display = "block";

                // клик по элементу
                suggestBox
                    .querySelectorAll(".inv-suggestion-item")
                    .forEach((item) => {
                        item.addEventListener("click", () => {
                            row.querySelector('input[name="name[]"]').value =
                                item.dataset.name;
                            row.querySelector('input[name="unit[]"]').value =
                                item.dataset.unit;
                            row.querySelector('input[name="price[]"]').value =
                                item.dataset.price;
                            invInput.value = item.dataset.inv;

                            // скрываем подсказки
                            suggestBox.style.display = "none";
                            suggestBox.innerHTML = "";

                            // показываем остаток
                            if (row._showStock) row._showStock();
                        });
                    });
            }, 300)
        );
    }

    productsWrapper
        .querySelectorAll(".operation-form__row--product")
        .forEach(attachInvAutocomplete);

    // ==============================
    // Ajax формы
    // ==============================
    $(document).on("ajaxSuccess", form, function (event, context, data) {
        handleServerResponse(data);
        localStorage.removeItem(storageKey);
        localStorage.removeItem("selectedProducts");
    });
});
