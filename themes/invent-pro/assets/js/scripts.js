document.addEventListener("DOMContentLoaded", () => {
    // =============================
    // Универсальный обработчик ответа с сервера
    // =============================
    function handleServerResponse(data) {
        // --- Сбрасываем старые ошибки ---
        document
            .querySelectorAll(".error-field")
            .forEach((el) => el.classList.remove("error-field"));
        document
            .querySelectorAll(".error-message")
            .forEach((el) => el.remove());

        // --- Подсветка только первой ошибки из validationErrors ---
        if (data.validationErrors && data.validationErrors.length) {
            const err = data.validationErrors[0]; // берём первую ошибку
            let input = null;

            // Тип операции
            if (err.field === "type_id") {
                toast(err.message, "error", 4000, "top-center");
                return;
            }

            // Контрагент
            if (err.field === "counteragent") {
                input = document.querySelector('input[name="counteragent"]');
            }

            // Поля массивов типа name[0]
            const match = err.field.match(/([^\[]+)\[(\d+)\]/);
            if (match) {
                const name = match[1];
                const index = parseInt(match[2], 10);
                const inputs = document.querySelectorAll(
                    `input[name = "${name}[]"]`
                );
                if (inputs[index]) input = inputs[index];
            }

            // Простые поля
            if (!input) {
                input = document.querySelector(`input[name = "${err.field}"]`);
            }

            if (input) {
                input.classList.add("error-field");

                // Добавляем текст ошибки под полем
                const errorDiv = document.createElement("div");
                errorDiv.classList.add("error-message");
                errorDiv.textContent = err.message;

                if (input.closest(".form-floating")) {
                    input.closest(".form-floating").appendChild(errorDiv);
                } else {
                    input.parentNode.appendChild(errorDiv);
                }

                // Фокус на поле
                input.focus();
            }
        }

        // --- Модалка ---
        if (data.modalContent) {
            Modal.show(
                data.modalContent,
                data.modalType || "info",
                data.modalTitle || "Результат"
            );
        }

        // --- Toast ---
        if (data.toast && data.toast.message) {
            const t = data.toast;
            toast(
                t.message,
                t.type || "info",
                t.timeout || 4000,
                t.position || "bottom-right"
            );
        }

        // --- @js код ---
        if (data["@js"]) {
            try {
                new Function(data["@js"])();
            } catch (e) {
                console.error("Ошибка выполнения @js из ответа:", e);
            }
        }
    }

    // =============================
    // Автоснятие ошибки при вводе
    // =============================
    const formInputsObserver = () => {
        document
            .querySelectorAll("#addOperationForm input")
            .forEach((input) => {
                input.removeEventListener("input", input._removeErrorClass);
                input._removeErrorClass = () => {
                    input.classList.remove("error-field");
                    const errorDiv = input
                        .closest(".form-floating")
                        ?.querySelector(".error-message");
                    if (errorDiv) errorDiv.remove();
                };
                input.addEventListener("input", input._removeErrorClass);
            });
    };
    formInputsObserver(); // применяем сразу

    // =============================
    // Остальной твой JS остаётся без изменений
    // =============================
    const importInput = document.getElementById("importInput");
    const importForm = document.getElementById("importForm");
    const importButton = document.getElementById("importButton");

    if (importButton && importInput) {
        importButton.addEventListener("click", () => importInput.click());
    }

    if (importInput && importForm) {
        importInput.addEventListener("change", () => {
            if (importInput.files.length > 0) {
                $(importForm).request("onImportExcel", {
                    success: handleServerResponse,
                });
            }
        });
    }

    const btnAddOperation = document.getElementById("btnAddOperation");
    if (btnAddOperation) {
        btnAddOperation.addEventListener("click", () => {
            Modal.show(
                "<p>Хочешь добавить операцию, братик?</p>",
                "info",
                "Добавить операцию!"
            );
        });
    }

    const showToastButton = document.getElementById("showToastButton");
    if (showToastButton) {
        showToastButton.addEventListener("click", () =>
            toast("Проверка Тоста", "error", 4000)
        );
    }

    const documentsWrapper = document.getElementById(
        "add-operation__documents-wrapper"
    );
    const addDocumentBtn = document.getElementById("add-document");
    const removeDocumentBtn = document.getElementById("remove-document");

    if (addDocumentBtn && documentsWrapper) {
        addDocumentBtn.addEventListener("click", () => {
            const first = documentsWrapper.querySelector(
                ".add-operation__row--document"
            );
            const clone = first.cloneNode(true);
            clone.querySelectorAll("input").forEach((i) => (i.value = ""));
            documentsWrapper.appendChild(clone);
            formInputsObserver();
        });
    }

    if (removeDocumentBtn && documentsWrapper) {
        removeDocumentBtn.addEventListener("click", () => {
            const items = documentsWrapper.querySelectorAll(
                ".add-operation__row--document"
            );
            if (items.length > 1) items[items.length - 1].remove();
        });
    }

    const productsWrapper = document.getElementById(
        "add-operation__products-wrapper"
    );
    const addProductBtn = document.getElementById("add-product");
    const removeProductBtn = document.getElementById("remove-product");

    if (addProductBtn && productsWrapper) {
        addProductBtn.addEventListener("click", () => {
            const first = productsWrapper.querySelector(
                ".add-operation__row--product"
            );
            const clone = first.cloneNode(true);
            clone.querySelectorAll("input").forEach((i) => (i.value = ""));
            productsWrapper.appendChild(clone);
            formInputsObserver();
        });
    }

    if (removeProductBtn && productsWrapper) {
        removeProductBtn.addEventListener("click", () => {
            const items = productsWrapper.querySelectorAll(
                ".add-operation__row--product"
            );
            if (items.length > 1) items[items.length - 1].remove();
        });
    }

    document.addEventListener("click", (event) => {
        if (event.target && event.target.id === "apply-differences") {
            const selected = Array.from(
                document.querySelectorAll(".diff-checkbox:checked")
            ).map((cb) => ({
                inv_number: cb.value,
                quantity: parseFloat(cb.dataset.quantity),
                price: parseFloat(cb.dataset.price),
                sum: parseFloat(cb.dataset.sum),
            }));

            if (!selected.length) return alert("Выберите хотя бы один продукт");

            $.request("onApplyDifferences", {
                data: { updates: selected },
                success: handleServerResponse,
            });
        }
    });

    $(document).on(
        "ajaxSuccess",
        "#addOperationForm",
        function (event, context, data) {
            handleServerResponse(data);
        }
    );
});
