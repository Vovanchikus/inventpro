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
        "#addOperationForm, #editOperationForm",
    );
    if (!form) return;

    const isEdit = form.id === "editOperationForm";

    // ==============================
    // Основные элементы формы
    // ==============================
    const addProductBtn = document.getElementById("add-product");
    const addFromDBBtn = document.getElementById("btnSearchProduct");
    const productsWrapper = document.getElementById(
        "operation-form__products-wrapper",
    );
    const documentsWrapper = document.getElementById(
        "operation-form__documents-wrapper",
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

    // ==============================
    // Кастомный дропдаун названий документов
    // ==============================
    const docNameOptions = window.docNameOptions || {};
    const docNameDefaultLabel = "Выберите документ";

    function getSelectedTypeName() {
        const checked = document.querySelector('input[name="type_id"]:checked');
        return (
            checked?.nextElementSibling?.textContent?.trim().toLowerCase() || ""
        );
    }

    function getDocNameListByType() {
        const typeName = getSelectedTypeName();
        if (typeName.includes("приход") || typeName.includes("передача")) {
            return docNameOptions.incoming_transfer || [];
        }
        if (typeName.includes("списание")) {
            return docNameOptions.writeoff || [];
        }
        return [];
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function initDocNameSelect(select) {
        if (!select || select.dataset.initialized) return;
        select.dataset.initialized = "1";

        const selected = select.querySelector(".selected");
        const options = select.querySelector(".options");
        const hidden =
            select
                .closest(".form-floating")
                ?.querySelector('input[name="doc_name[]"]') ||
            select.parentElement?.querySelector('input[name="doc_name[]"]');

        selected?.addEventListener("click", (e) => {
            e.stopPropagation();
            select.classList.toggle("active");
        });

        options?.addEventListener("click", (e) => {
            const option = e.target.closest(".option");
            if (!option) return;
            const value = option.dataset.value || "";
            if (hidden) hidden.value = value;
            if (selected) selected.textContent = option.textContent;
            select.classList.remove("active");
            select.classList.remove("error-field");
            selected?.classList.remove("error-field");
        });
    }

    function refreshDocNameSelect(select) {
        if (!select) return;
        const options = select.querySelector(".options");
        const selected = select.querySelector(".selected");
        const hidden =
            select
                .closest(".form-floating")
                ?.querySelector('input[name="doc_name[]"]') ||
            select.parentElement?.querySelector('input[name="doc_name[]"]');

        const list = getDocNameListByType();
        if (options) {
            if (list.length) {
                options.innerHTML = list
                    .map((item) => {
                        const safe = escapeHtml(item);
                        return `<div class="option" data-value="${safe}">${safe}</div>`;
                    })
                    .join("");
            } else {
                options.innerHTML = "";
            }
        }

        const currentValue = hidden?.value || "";
        if (currentValue && (list.includes(currentValue) || !list.length)) {
            if (selected) selected.textContent = currentValue;
        } else {
            if (selected) selected.textContent = docNameDefaultLabel;
            if (hidden) hidden.value = "";
        }
    }

    function refreshAllDocNameSelects() {
        document
            .querySelectorAll(".doc-name-select")
            .forEach((select) => refreshDocNameSelect(select));
    }

    document.addEventListener("click", (e) => {
        document.querySelectorAll(".doc-name-select.active").forEach((sel) => {
            if (!sel.contains(e.target)) sel.classList.remove("active");
        });
    });
    document.querySelectorAll('input[name="type_id"]').forEach((r) =>
        r.addEventListener("change", () => {
            updateProductButtons();
            updateCounteragentVisibility();
            refreshAllDocNameSelects();
        }),
    );
    updateProductButtons();
    updateCounteragentVisibility();
    refreshAllDocNameSelects();

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
        ".remove-document-btn",
    );
    handleRemoveButton(
        productsWrapper,
        ".operation-form__row--product",
        ".remove-product-btn",
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
        .forEach((row) => {
            setupFileUpload(row);
            const select = row.querySelector(".doc-name-select");
            initDocNameSelect(select);
            refreshDocNameSelect(select);
        });

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

                const select = clone.querySelector(".doc-name-select");
                if (select) {
                    select.dataset.initialized = "";
                    initDocNameSelect(select);
                    refreshDocNameSelect(select);
                }
            }

            wrapper.appendChild(clone);

            // Обновляем кнопки удаления
            updateRemoveButtons(
                wrapper,
                rowSelector,
                rowSelector.includes("product")
                    ? ".remove-product-btn"
                    : ".remove-document-btn",
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
        ".operation-form__row--product",
    );
    attachRowEvents(
        documentsWrapper,
        document.getElementById("add-document"),
        ".operation-form__row--document",
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
                        (p) => p.inv_number === invNumber,
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
            r.addEventListener("change", () => setTimeout(initAutoSumCalc, 50)),
        );
    addProductBtn?.addEventListener("click", () =>
        setTimeout(initAutoSumCalc, 50),
    );
    initAutoSumCalc();

    // ==============================
    // Добавление товара в операцию
    // ==============================
    function addProductToOperation(product) {
        let emptyRow = Array.from(
            productsWrapper.querySelectorAll(".operation-form__row--product"),
        ).find((row) => row.querySelector('input[name="name[]"]').value === "");

        if (!emptyRow) {
            const firstRow = productsWrapper.querySelector(
                ".operation-form__row--product",
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
    // Если в URL есть note_id или сервер передал window.prefill_products,
    // то приоритет у них — игнорируем старые localStorage и очищаем ключи.
    // ==============================
    const storageKey = isEdit ? "editOperation" : "createOperation";
    const storedProducts = JSON.parse(localStorage.getItem(storageKey) || "[]");
    const urlHasNote = /[?&]note_id=/.test(window.location.search || "");
    // Не удаляем сразу локальное хранилище при наличии note_id: если сервер не вернёт prefill,
    // то используем локально сохранённые `createOperation` как fallback.
    if (
        window.prefill_products &&
        Array.isArray(window.prefill_products) &&
        window.prefill_products.length
    ) {
        try {
            localStorage.removeItem(storageKey);
        } catch (e) {}
    }
    // Helper: check if form already contains product with same inv_number
    function formHasProduct(invNumber) {
        if (!invNumber) return false;
        const rows = Array.from(
            productsWrapper.querySelectorAll(".operation-form__row--product"),
        );
        return rows.some((row) => {
            const val = (
                row.querySelector('input[name="inv_number[]"]')?.value || ""
            ).trim();
            return val && val === String(invNumber);
        });
    }

    if (storedProducts.length) {
        // Если сервер не предоставил prefill — используем локально сохранённые товары
        if (
            !(
                window.prefill_products &&
                Array.isArray(window.prefill_products) &&
                window.prefill_products.length
            )
        ) {
            storedProducts.forEach((p) => {
                const inv = p.inv_number || p.inv || p.id;
                if (!formHasProduct(inv)) addProductToOperation(p);
            });
            localStorage.removeItem(storageKey);
        }
    }

    // ==============================
    // Prefill from note (server-side variable window.prefill_products)
    // ==============================
    try {
        if (
            window.prefill_products &&
            Array.isArray(window.prefill_products) &&
            window.prefill_products.length
        ) {
            window.prefill_products.forEach((p) => {
                const inv = p.inv_number || p.inv || p.id;
                if (!formHasProduct(inv)) addProductToOperation(p);
            });
            // attach note_id hidden input so backend knows the source note
            if (window.prefill_note_id) {
                let existing = form.querySelector('input[name="note_id"]');
                if (!existing) {
                    const h = document.createElement("input");
                    h.type = "hidden";
                    h.name = "note_id";
                    h.value = window.prefill_note_id;
                    form.appendChild(h);
                }
            }

            // cleanup to avoid re-inserting on reload
            try {
                localStorage.removeItem("createOperation");
            } catch (e) {}
            try {
                localStorage.removeItem("selectedProducts");
            } catch (e) {}
        }
    } catch (e) {}

    // ==============================
    // Prefill operation details (type, counteragent, documents)
    // ==============================
    try {
        if (window.prefill_operation) {
            const typeId = window.prefill_operation.type_id;
            if (typeId) {
                document
                    .querySelectorAll('input[name="type_id"]')
                    .forEach((r) => {
                        if (String(r.value) === String(typeId)) {
                            r.checked = true;
                            r.dispatchEvent(new Event("change"));
                        }
                    });
            }

            const counteragentInput = form.querySelector(
                'input[name="counteragent"]',
            );
            if (counteragentInput) {
                counteragentInput.value =
                    window.prefill_operation.counteragent || "";
            }
        }

        if (Array.isArray(window.prefill_documents)) {
            const docs = window.prefill_documents;
            if (docs.length && documentsWrapper) {
                const firstRow = documentsWrapper.querySelector(
                    ".operation-form__row--document",
                );

                const fillRow = (row, doc) => {
                    const nameInput = row.querySelector(
                        'input[name="doc_name[]"]',
                    );
                    const nameSelected = row.querySelector(
                        ".doc-name-select .selected",
                    );
                    if (nameInput) nameInput.value = doc.doc_name || "";
                    if (nameSelected)
                        nameSelected.textContent = doc.doc_name || "";

                    row.querySelector('input[name="doc_num[]"]').value =
                        doc.doc_num || "";
                    row.querySelector('input[name="doc_purpose[]"]').value =
                        doc.doc_purpose || "";
                    row.querySelector('input[name="doc_date[]"]').value =
                        doc.doc_date || "";
                };

                // fill first row
                fillRow(firstRow, docs[0]);

                // add rows for remaining docs
                for (let i = 1; i < docs.length; i++) {
                    const clone = firstRow.cloneNode(true);
                    clone.querySelectorAll("input").forEach((i) => {
                        if (i.type !== "file") i.value = "";
                    });
                    fillRow(clone, docs[i]);
                    documentsWrapper.appendChild(clone);
                    setupFileUpload(clone);
                }
            }
        }
    } catch (e) {}

    // Если в URL передан тип операции (например ?type=приход) — попытаемся выбрать его
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const desiredType = urlParams.get("type");
        if (desiredType) {
            document.querySelectorAll('input[name="type_id"]').forEach((r) => {
                const label = r.nextElementSibling?.textContent
                    ?.trim()
                    ?.toLowerCase();
                if (label === desiredType.trim().toLowerCase()) {
                    r.checked = true;
                    r.dispatchEvent(new Event("change"));
                }
            });
        }
    } catch (e) {
        // ignore
    }

    // ==============================
    // По номенклатурному номеру проверяем есть ли такой товар на складе на странице добавлении и редактировании операции
    // ==============================
    function attachInvAutocomplete(row) {
        const invInput = row.querySelector('input[name="inv_number[]"]');

        // создаём подсказку только для этой строки
        let suggestBox = row.querySelector(
            ".operation-form__input--inv-suggestion",
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
                    p.inv_number.startsWith(value),
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
                `,
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
            }, 300),
        );
    }

    productsWrapper
        .querySelectorAll(".operation-form__row--product")
        .forEach(attachInvAutocomplete);

    // ==============================
    // Ajax формы
    // ==============================
    $(document).on("ajaxSuccess", form, function (event, context, data) {
        if (data?.validationErrors?.length) {
            try {
                console.warn("[operation-form] validation errors:", {
                    errors: data.validationErrors,
                });
            } catch (e) {}

            const highlightDocField = (field) => {
                if (!documentsWrapper) return null;

                const baseMatch = String(field).match(/^(doc_[^\.\[]+)/);
                const base = baseMatch ? baseMatch[1] : "";
                const indexMatch = String(field).match(/[\.\[]\s*(\d+)/);
                const index = indexMatch ? parseInt(indexMatch[1], 10) : 0;

                const rows = documentsWrapper.querySelectorAll(
                    ".operation-form__row--document",
                );
                const row = rows[index] || rows[0];
                if (!row) return null;

                const mark = (el) => {
                    if (!el) return null;
                    el.classList.add("error-field");
                    return el;
                };

                if (base === "doc_name") {
                    const selected = row.querySelector(
                        ".doc-name-select .selected",
                    );
                    const hidden = row.querySelector(
                        'input[name="doc_name[]"]',
                    );
                    mark(selected);
                    mark(hidden);
                    return selected || hidden;
                }

                if (base === "doc_num") {
                    return mark(row.querySelector('input[name="doc_num[]"]'));
                }

                if (base === "doc_purpose") {
                    return mark(
                        row.querySelector('input[name="doc_purpose[]"]'),
                    );
                }

                if (base === "doc_date") {
                    return mark(row.querySelector('input[name="doc_date[]"]'));
                }

                return null;
            };

            const getBaseField = (field) => {
                const match = field?.match(/([^\[]+)/);
                return match ? match[1] : field;
            };

            const getPriority = (field) => {
                if (field === "type_id") return 1;
                if (field === "counteragent") return 2;
                if (field && field.startsWith("doc_")) return 3;

                const base = getBaseField(field);
                if (
                    ["name", "inv_number", "price", "quantity"].includes(base)
                ) {
                    return 4;
                }

                return 99;
            };

            const orderedErrors = [...data.validationErrors].sort(
                (a, b) => getPriority(a.field) - getPriority(b.field),
            );

            const err = orderedErrors[0];
            try {
                console.warn("[operation-form] prioritized error:", {
                    field: err?.field,
                    message: err?.message,
                    priority: getPriority(err?.field),
                });
            } catch (e) {}
            let input = null;

            if (typeof window.toast === "function") {
                window.toast(err.message, "error", 4000, "top-center");
            }

            if (err.field === "counteragent") {
                input = document.querySelector('input[name="counteragent"]');
            }

            if (err.field?.startsWith("doc_")) {
                const target = highlightDocField(err.field);
                if (target) {
                    target.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                    if (typeof target.focus === "function") target.focus();
                }
            } else {
                const match = err.field.match(/([^\[]+)\[(\d+)\]/);
                if (match) {
                    const name = match[1];
                    const index = parseInt(match[2], 10);
                    const inputs = document.querySelectorAll(
                        `input[name = "${name}[]"]`,
                    );
                    if (inputs[index]) input = inputs[index];
                }

                if (!input) {
                    input = document.querySelector(
                        `input[name = "${err.field}"]`,
                    );
                }
            }

            if (input) {
                showFieldMessage(input, err.message, "error");
                input.scrollIntoView({ behavior: "smooth", block: "center" });
                input.focus();
            }

            return;
        }

        handleServerResponse(data);
        localStorage.removeItem(storageKey);
        localStorage.removeItem("selectedProducts");
    });

    // ==============================
    // Управление видимостью поля "Контрагент"
    // ==============================
    function updateCounteragentVisibility() {
        const checked = document.querySelector('input[name="type_id"]:checked');
        if (!checked) return;

        const typeName = checked.nextElementSibling?.textContent
            .trim()
            .toLowerCase();

        const counteragentBox = document.querySelector(
            ".operation-form__counteragent",
        );
        const counteragentInput = counteragentBox?.querySelector(
            'input[name="counteragent"]',
        );

        if (!counteragentBox || !counteragentInput) return;

        if (typeName.includes("списание")) {
            counteragentBox.style.display = "none";
            counteragentInput.value = "";
        } else {
            counteragentBox.style.display = "";
        }
    }
});
