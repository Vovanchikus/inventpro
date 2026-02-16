document.addEventListener("DOMContentLoaded", () => {
    // Читаем GET-параметры
    const urlParams = new URLSearchParams(window.location.search);

    const params = {
        type: urlParams.get("type") || "",
        counteragent: urlParams.get("counteragent") || "",
        year: urlParams.get("year") || "",
    };

    // Устанавливаем значения в hidden input
    Object.keys(params).forEach((key) => {
        const input = document.getElementById(key + "Input");
        if (input) {
            input.value = params[key];
        }
    });

    const selects = document.querySelectorAll(".custom-select");

    selects.forEach((sel) => {
        const selected = sel.querySelector(".selected");
        const options = sel.querySelector(".options");
        const paramName = sel.dataset.name; // type / counteragent / year

        // Установка выбранного значения из URL
        const currentValue = params[paramName];
        if (currentValue) {
            const option = options.querySelector(
                `.option[data-value="${CSS.escape(currentValue)}"]`,
            );
            if (option) {
                selected.innerHTML = option.innerHTML;
                sel.dataset.value = currentValue;
            }
        }

        // Открытие / закрытие dropdown
        selected.addEventListener("click", (e) => {
            e.stopPropagation();
            sel.classList.toggle("active");
        });

        // Выбор значения
        options.querySelectorAll(".option").forEach((option) => {
            option.addEventListener("click", () => {
                const value = option.dataset.value || "";

                selected.innerHTML = option.innerHTML;
                sel.dataset.value = value;
                sel.classList.remove("active");

                // Записываем значение в hidden input
                const input = document.getElementById(paramName + "Input");
                if (input) {
                    input.value = value;
                }
            });
        });
    });

    // Закрытие всех dropdown при клике вне
    document.addEventListener("click", () => {
        selects.forEach((sel) => sel.classList.remove("active"));
    });

    // Подтверждение удаления черновика через модалку
    document.addEventListener("click", (event) => {
        const btn = event.target.closest(".js-delete-draft");
        if (!btn) return;

        const operationId = btn.dataset.operationId;
        if (!operationId) return;

        const html = `
            <div class="modal-box">
                <p>Удалить черновик?</p>
                <div class="modal-actions">
                    <button type="button" class="btn" id="confirmDeleteDraft">Удалить</button>
                    <button type="button" class="btn btn-secondary" id="cancelDeleteDraft">Отмена</button>
                </div>
            </div>
        `;

        Modal.show(html, "info", "Подтвердите удаление");

        document
            .getElementById("cancelDeleteDraft")
            ?.addEventListener("click", () => Modal.hide());

        document
            .getElementById("confirmDeleteDraft")
            ?.addEventListener("click", () => {
                $.request("operationInfo::onDeleteDraft", {
                    data: { id: operationId },
                    success(res) {
                        handleServerResponse(res);
                        Modal.hide();
                        const item = btn.closest(".documents-item");
                        if (item) item.remove();
                    },
                    error(xhr) {
                        handleServerResponse(xhr.responseJSON || {});
                    },
                });
            });
    });
});
