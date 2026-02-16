document.addEventListener("click", (e) => {
    const btn = e.target.closest(".addOperationForNotes");
    if (!btn) return; // если клик не по кнопке, выходим

    const noteId = btn.dataset.noteId;
    if (!noteId) {
        console.error("note_id не найден на кнопке");
        return;
    }

    $.request("onShowAddOperationModal", {
        data: { note_id: noteId },
        success(resp) {
            if (resp && resp.modalContent) {
                Modal.show(resp.modalContent, "info", "Добавить операцию");
                initModalForm("onAddToExistingNote");
            } else {
                console.error("Нет контента для модалки");
            }
        },
        error() {
            toast("Ошибка при запросе формы", "error");
        },
    });
});

// Обработчик для кнопки добавления операции внутри модалки (перенесён из handlers.js)
document.addEventListener("click", (e) => {
    const btn = e.target.closest("#add-operation-btn");
    if (!btn) return;

    const noteId = btn.dataset.noteId || btn.getAttribute("data-note-id");
    const selected = document.querySelector(
        `.modal .modal-content input[name="operation_id"]:checked`
    );

    if (!selected) {
        toast("Выберите операцию", "error");
        return;
    }

    const operationId = selected.value;

    $.request("workflowNotesFrontend::onAddOperationToNote", {
        data: {
            note_id: noteId,
            operation_id: operationId,
        },
        success(res) {
            if (typeof handleServerResponse === "function")
                handleServerResponse(res);
            if (res && res.success) {
                Modal.hide();
            }
        },
        error() {
            toast("Ошибка при добавлении операции", "error");
        },
    });
});
