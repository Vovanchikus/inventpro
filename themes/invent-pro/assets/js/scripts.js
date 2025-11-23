document.addEventListener("DOMContentLoaded", () => {
    // =============================
    // Элементы на странице
    // =============================
    const importInput = document.getElementById("importInput");
    const importForm = document.getElementById("importForm");
    const importButton = document.getElementById("importButton");
    const btnAddOperation = document.getElementById("btnAddOperation");
    const showToastButton = document.getElementById("showToastButton");

    // =============================
    // Универсальный обработчик ответа с сервера
    // =============================
    function handleServerResponse(data) {
        // --- Модалка ---
        // Если сервер прислал HTML контент модалки, показываем
        if (data.modalContent) {
            Modal.show(
                data.modalContent,
                data.modalType || "info",
                data.modalTitle || "Результат"
            );
        }

        // --- Toast ---
        // Если сервер прислал toast
        if (data.toast && data.toast.message) {
            const t = data.toast;
            const message = t.message || "";
            const type = t.type || "info";
            const timeout = typeof t.timeout === "number" ? t.timeout : 4000;
            const position = t.position || "bottom-right";

            try {
                toast(message, type, timeout, position);
            } catch (e) {
                console.warn("Функция toast не найдена", e);
            }
        }

        // --- @js код ---
        // Сервер может прислать произвольный JS для выполнения
        if (data["@js"]) {
            try {
                new Function(data["@js"])();
            } catch (e) {
                console.error("Ошибка выполнения @js из ответа:", e);
            }
        }
    }

    // =============================
    // Кнопка "Импорт" — открывает диалог выбора файла
    // =============================
    importButton.addEventListener("click", () => {
        importInput.click();
    });

    // =============================
    // Авто-отправка формы при выборе файла
    // =============================
    importInput.addEventListener("change", () => {
        if (importInput.files.length > 0) {
            $(importForm).request("onImportExcel", {
                success: handleServerResponse, // Используем универсальный обработчик
                error: function (err) {
                    console.error("Ошибка AJAX при импорте:", err);
                    alert("Произошла ошибка при отправке файла");
                },
            });
        }
    });

    // =============================
    // Кнопка "Добавить операцию"
    // =============================
    if (btnAddOperation) {
        btnAddOperation.addEventListener("click", () => {
            Modal.show(
                `<p>Хочешь добавить операцию, братик?</p>`,
                "info",
                "Добавить операцию!"
            );
        });
    }

    if (showToastButton) {
        showToastButton.addEventListener("click", () => {
            toast("Проверка Тоста", "error", 4000);
        });
    }

    // =============================
    // Обработка кликов на странице
    // =============================
    document.addEventListener("click", function (event) {
        // --- Выбор всех чекбоксов различий ---
        if (event.target && event.target.id === "select-all-diffs") {
            const checked = event.target.checked;
            document
                .querySelectorAll(".diff-checkbox")
                .forEach((cb) => (cb.checked = checked));
        }

        // --- Применение выбранных различий ---
        if (event.target && event.target.id === "apply-differences") {
            const selected = Array.from(
                document.querySelectorAll(".diff-checkbox:checked")
            ).map((cb) => ({
                inv_number: cb.value,
                quantity: parseFloat(cb.dataset.quantity),
                price: parseFloat(cb.dataset.price),
                sum: parseFloat(cb.dataset.sum),
            }));

            if (selected.length === 0) {
                alert("Выберите хотя бы один продукт");
                return;
            }

            console.log("Отправляем данные:", selected);

            $.request("onApplyDifferences", {
                data: { updates: selected },
                success: handleServerResponse, // Универсальный обработчик
                error: function (err) {
                    console.error("Ошибка AJAX при применении различий:", err);
                    alert("Произошла ошибка при отправке данных");
                },
            });
        }
    });
});
