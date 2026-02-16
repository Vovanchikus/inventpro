/**
 * handlers.js
 * ----------
 * Универсальные функции для работы с формами
 * - showFieldMessage(input, message, type) — выводит сообщение под полем
 * - handleServerResponse(data) — обработка ответа с сервера (включает модалки и тосты)
 */

function showFieldMessage(input, message, type = "info") {
    const parent = input.closest(".form-floating") || input.parentNode;
    const oldMsg = parent.querySelector(".field-message");
    if (oldMsg) oldMsg.remove();

    const div = document.createElement("div");
    div.className = "field-message";
    if (type === "error") {
        div.classList.add("field-message--error");
        input.classList.add("error-field");
    } else {
        div.classList.add("field-message--info");
        input.classList.remove("error-field");
    }
    div.textContent = message;
    parent.appendChild(div);
}

function handleServerResponse(data) {
    // Сбрасываем старые сообщения
    document
        .querySelectorAll(".error-field")
        .forEach((el) => el.classList.remove("error-field"));
    document.querySelectorAll(".field-message").forEach((el) => el.remove());

    // Подсветка первой ошибки
    let skipToastForDoc = false;
    if (data.validationErrors && data.validationErrors.length) {
        const err = data.validationErrors[0];
        let input = null;

        if (err.field && err.field.startsWith("doc_")) {
            skipToastForDoc = true;
            return;
        }

        if (err.field === "type_id") {
            toast(err.message, "error", 4000, "top-center");
            return;
        }
        if (err.field === "counteragent") {
            input = document.querySelector('input[name="counteragent"]');
        }

        const match = err.field.match(/([^\[]+)\[(\d+)\]/);
        if (match) {
            const name = match[1];
            const index = parseInt(match[2], 10);
            const inputs = document.querySelectorAll(
                `input[name = "${name}[]"]`,
            );
            if (inputs[index]) input = inputs[index];
        }

        if (!input)
            input = document.querySelector(`input[name = "${err.field}"]`);

        if (input) {
            showFieldMessage(input, err.message, "error");
            input.scrollIntoView({ behavior: "smooth", block: "center" });
            input.focus();
        }
    }

    // Модалка
    if (data.modalContent) {
        Modal.show(
            data.modalContent,
            data.modalType || "info",
            data.modalTitle || "Результат",
            data.modalSubtitle || "",
            data.modalIconSvg || "",
        );
    }

    // Toast
    if (data.toast && data.toast.message && !skipToastForDoc) {
        const t = data.toast;
        toast(
            t.message,
            t.type || "info",
            t.timeout || 4000,
            t.position || "bottom-right",
        );
    }

    // @js код
    if (data["@js"]) {
        try {
            new Function(data["@js"])();
        } catch (e) {
            console.error("Ошибка выполнения @js:", e);
        }
    }
}

// NOTE: handler for add-operation moved to pages/home.js
