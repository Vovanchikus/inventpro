document.addEventListener("DOMContentLoaded", () => {
    const importInput = document.getElementById("importInput");
    const importForm = document.getElementById("importForm");
    const importButton = document.getElementById("importButton");
    const btnAddOperation = document.getElementById("btnAddOperation");

    // Кнопка "Импорт" — открывает файловый диалог
    importButton.addEventListener("click", () => {
        importInput.click();
    });

    // Авто-отправка формы при выборе файла
    importInput.addEventListener("change", () => {
        if (importInput.files.length > 0) {
            $(importForm).request("onImportExcel", {
                success: function (data) {
                    if (data.modalContent) {
                        Modal.show(
                            data.modalContent,
                            data.modalType || "info",
                            data.modalTitle || "Результат импорта"
                        );
                    }
                },
                error: function (err) {
                    Modal.show(
                        '<p style="color:red;">Ошибка сервера</p>',
                        "error",
                        "Ошибка!"
                    );
                    console.error(err);
                },
            });
        }
    });

    // Кнопка "Добавить операцию"
    if (btnAddOperation) {
        btnAddOperation.addEventListener("click", () => {
            Modal.show(
                `<p>Хочешь добавить операцию, братик?</p>`,
                "info",
                "Добавить операцию!"
            );
        });
    }
});
