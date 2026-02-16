document.addEventListener("DOMContentLoaded", () => {
    // Открыть модал выбора заметки
    function openChooseNoteModal(selectedProducts) {
        Modal.show(
            '<div class="notes-list-placeholder">Загрузка...</div>',
            "info",
            "Выберите заметку",
        );

        $.request("onListNotes", {
            success(res) {
                const container = document.querySelector(
                    "#modal-container .modal-content",
                );

                // If server returned rendered HTML partial — insert it
                if (res.notesHtml) {
                    container.innerHTML = res.notesHtml;
                } else {
                    const notes = res.notes || [];
                    if (!notes.length) {
                        container.innerHTML =
                            '<p>Заметок не найдено. <a href="#" class="btn" id="openCreateNoteFromModal">Создать</a></p>';
                        document
                            .getElementById("openCreateNoteFromModal")
                            ?.addEventListener("click", (e) => {
                                e.preventDefault();
                                openCreateNoteModal();
                            });
                        return;
                    }

                    // Fallback: build simple list (shouldn't be used normally)
                    const list = document.createElement("div");
                    list.className = "notes-list";
                    notes.forEach((n) => {
                        const card = document.createElement("div");
                        card.className = "note-card";
                        card.dataset.id = n.id;
                        card.innerHTML = `<h4>${n.title || "Без названия"}</h4><div class="note-meta">${n.due_date ? "Срок: " + n.due_date : ""}</div><p>${n.description || ""}</p><div class="note-actions"><button class="btn btn-add-to-note">Добавить выбранные</button></div>`;
                        list.appendChild(card);
                    });
                    container.innerHTML = "";
                    container.appendChild(list);
                }

                // Attach click handlers for add buttons
                container
                    .querySelectorAll(".btn-add-to-note")
                    .forEach((btn) => {
                        btn.addEventListener("click", (e) => {
                            const card = e.target.closest(".note-card");
                            const noteId = card?.dataset?.id;
                            if (!noteId) return;

                            $.request("onAddProductsToNote", {
                                data: {
                                    note_id: noteId,
                                    products: JSON.stringify(selectedProducts),
                                },
                                success(resp) {
                                    handleServerResponse(resp);
                                    Modal.hide();
                                    localStorage.removeItem("selectedProducts");
                                    document
                                        .querySelectorAll(".product-check")
                                        .forEach((cb) => (cb.checked = false));
                                    document
                                        .querySelectorAll(".bottom-bar__close")
                                        .forEach((b) => b.click());
                                },
                            });
                        });
                    });
            },
            error() {
                const container = document.querySelector(
                    "#modal-container .modal-content",
                );
                container.innerHTML =
                    "<p>Ошибка при загрузке списка заметок</p>";
            },
        });
    }

    // Заменим prompt-логику в bottom-bar.js — слушаем событие, чтобы открывать модал
    document.addEventListener("click", (e) => {
        const btn = e.target.closest("#addToNote");
        if (!btn) return;

        const selected = JSON.parse(
            localStorage.getItem("selectedProducts") || "[]",
        );
        if (!selected.length) {
            toast("Выберите хотя бы один товар!", "error");
            return;
        }

        openChooseNoteModal(selected);
    });

    // Открыть модал создания заметки
    function openCreateNoteModal(prefillProducts) {
        Modal.show(
            '<div class="notes-list-placeholder">Загрузка...</div>',
            "info",
            "Створення нотатки",
            "Цей процес потрібний для відстеження руху документів",
            `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.5 10.19H17.61C15.24 10.19 13.31 8.26 13.31 5.89V3C13.31 2.45 12.86 2 12.31 2H8.07C4.99 2 2.5 4 2.5 7.57V16.43C2.5 20 4.99 22 8.07 22H15.93C19.01 22 21.5 20 21.5 16.43V11.19C21.5 10.64 21.05 10.19 20.5 10.19Z" fill="currentColor"/>
                <path d="M15.8 2.21C15.39 1.8 14.68 2.08 14.68 2.65V6.14C14.68 7.6 15.92 8.81 17.43 8.81C18.38 8.82 19.7 8.82 20.83 8.82C21.4 8.82 21.7 8.15 21.3 7.75C19.86 6.3 17.28 3.69 15.8 2.21Z" fill="currentColor"/>
            </svg>`,
        );

        $.request("onShowCreateModal", {
            data: {
                selected_products: prefillProducts || [],
            },
            success(res) {
                const container = document.querySelector(
                    "#modal-container .modal-content",
                );

                if (!container) return;

                if (res && res.modalContent) {
                    container.innerHTML = res.modalContent;
                }

                const form = document.getElementById("modalCreateNoteForm");
                if (!form) return;

                form.addEventListener("submit", (ev) => {
                    ev.preventDefault();
                    const formData = new FormData(form);
                    // determine if products were provided
                    let hasProducts = false;
                    if (prefillProducts) {
                        formData.append(
                            "products",
                            JSON.stringify(prefillProducts),
                        );
                        hasProducts = Array.isArray(prefillProducts)
                            ? prefillProducts.length > 0
                            : !!prefillProducts;
                    } else {
                        const stored =
                            localStorage.getItem("createNote") ||
                            localStorage.getItem("selectedProducts");
                        if (stored) {
                            formData.append("products", stored);
                            try {
                                const parsed = JSON.parse(stored);
                                hasProducts = Array.isArray(parsed)
                                    ? parsed.length > 0
                                    : !!parsed;
                            } catch (e) {
                                hasProducts = stored.length > 0;
                            }
                        }
                    }

                    const data = {};
                    formData.forEach((v, k) => (data[k] = v));

                    $.request("onCreateNote", {
                        data: data,
                        success(res) {
                            handleServerResponse(res);
                            const noteId = res.note_id;
                            // Показываем опции только если были товары
                            if (!hasProducts) {
                                Modal.hide();
                                localStorage.removeItem("createNote");
                                localStorage.removeItem("selectedProducts");
                                document
                                    .querySelectorAll(".product-check")
                                    .forEach((cb) => (cb.checked = false));
                                return;
                            }

                            const createNowHtml = `
                        <div class="modal-box">
                            <p>Заметка сохранена.</p>
                            <div class="modal-actions">
                                <button class="btn" id="createOperationNow">Создать операцию сейчас</button>
                                <button class="btn btn-secondary" id="createOperationLater">Создать позже</button>
                            </div>
                        </div>
                    `;

                            Modal.show(createNowHtml, "info", "Готово");

                            document
                                .getElementById("createOperationNow")
                                ?.addEventListener("click", () => {
                                    // Перейти на страницу создания операции с prefill: сохраняем товары в localStorage и редирект
                                    const stored =
                                        localStorage.getItem("createNote") ||
                                        localStorage.getItem(
                                            "selectedProducts",
                                        );
                                    if (stored) {
                                        // сохраним временно под ключом createOperation
                                        try {
                                            localStorage.setItem(
                                                "createOperation",
                                                stored,
                                            );
                                        } catch (e) {}
                                    }

                                    // редирект на страницу создания операции с note_id в параметре
                                    window.location.href =
                                        "/add-operation?note_id=" + noteId;
                                });

                            document
                                .getElementById("createOperationLater")
                                ?.addEventListener("click", () => {
                                    Modal.hide();
                                });
                        },
                    });
                });
            },
            error() {
                const container = document.querySelector(
                    "#modal-container .modal-content",
                );
                if (container) {
                    container.innerHTML =
                        "<p>Ошибка при загрузке формы создания заметки</p>";
                }
            },
        });
    }

    // Подключаем обработку клика по кнопке создать заметку внизу
    document.addEventListener("click", (e) => {
        const btn = e.target.closest("#createNote");
        if (!btn) return;

        const selected = JSON.parse(
            localStorage.getItem("selectedProducts") || "[]",
        );
        openCreateNoteModal(selected.length ? selected : null);
    });

    // Обработчик header "Создать заметку" (id openCreateNoteHeader)
    document.addEventListener("click", (e) => {
        const btn = e.target.closest("#openCreateNoteHeader");
        if (!btn) return;
        e.preventDefault();
        const selected = JSON.parse(
            localStorage.getItem("selectedProducts") || "[]",
        );
        openCreateNoteModal(selected.length ? selected : null);
    });
});
