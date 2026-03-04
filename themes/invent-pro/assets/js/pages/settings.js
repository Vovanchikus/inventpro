document.addEventListener("DOMContentLoaded", () => {
    const root = document.querySelector(".settings-page");
    if (!root) return;

    const tabs = Array.from(root.querySelectorAll(".settings-page__tab"));
    const panes = Array.from(root.querySelectorAll(".settings-page__pane"));

    const api = {
        saveField: "/api/settings/document-template/field",
        addPerson: "/api/settings/document-template/person/add",
        updatePerson: "/api/settings/document-template/person/update",
        deletePerson: "/api/settings/document-template/person/delete",
        selectPerson: "/api/settings/document-template/person/select",
    };

    function getTemplateHtml(templateId) {
        const template = document.getElementById(templateId);
        if (!template) return "";

        if (template.tagName === "TEMPLATE") {
            return template.innerHTML;
        }

        return template.innerHTML || "";
    }

    function setActiveTab(tabKey) {
        tabs.forEach((tab) => {
            const isActive = tab.dataset.tabTarget === tabKey;
            tab.classList.toggle("is-active", isActive);
        });

        panes.forEach((pane) => {
            const isActive = pane.dataset.tabContent === tabKey;
            pane.style.display = isActive ? "" : "none";
        });
    }

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            setActiveTab(tab.dataset.tabTarget || "personnel");
        });
    });

    function getCsrfToken() {
        return (
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") ||
            document.querySelector('input[name="_token"]')?.value ||
            ""
        );
    }

    async function requestJson(url, options = {}) {
        const method = (options.method || "GET").toUpperCase();
        const headers = {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            ...options.headers,
        };

        if (method !== "GET") {
            headers["X-CSRF-TOKEN"] = getCsrfToken();
            headers["Content-Type"] = "application/json";
        }

        const response = await fetch(url, {
            ...options,
            method,
            headers,
            body:
                method !== "GET" && options.body
                    ? JSON.stringify(options.body)
                    : undefined,
        });

        const payload = await response.json();
        if (!response.ok || payload.success === false) {
            throw new Error(payload.error || "Помилка запиту");
        }

        return payload.data || {};
    }

    function notify(message, type = "success", duration = 3000) {
        if (typeof toast === "function") {
            toast(message, type, duration, "top-center");
        }
    }

    function hardReload() {
        window.location.reload();
    }

    function openPersonModal(
        { title, submitLabel, initialName = "", initialPosition = "" },
        onSubmit,
    ) {
        const modalHtml = getTemplateHtml("settings-person-modal-template");
        if (
            typeof Modal === "undefined" ||
            typeof Modal.show !== "function" ||
            !modalHtml
        ) {
            const fallbackName = window.prompt(
                "Ім’я та прізвище",
                initialName || "",
            );
            if (!fallbackName) return;
            const fallbackPosition = window.prompt(
                "Посада",
                initialPosition || "",
            );
            onSubmit({
                name: fallbackName,
                position: fallbackPosition || "",
            });
            return;
        }

        Modal.show(modalHtml, "info", title, "");

        const content = document.querySelector(
            "#modal-container .modal-content",
        );
        if (!content) {
            return;
        }

        const nameInput = content.querySelector("#settings-person-modal-name");
        const positionInput = content.querySelector(
            "#settings-person-modal-position",
        );
        const cancelBtn = content.querySelector(
            "#settings-person-modal-cancel",
        );
        const saveBtn = content.querySelector("#settings-person-modal-save");
        const submitLabelEl = content.querySelector(
            '[data-role="submit-label"]',
        );

        if (nameInput) {
            nameInput.value = String(initialName || "");
        }

        if (positionInput) {
            positionInput.value = String(initialPosition || "");
        }

        if (submitLabelEl) {
            submitLabelEl.textContent = String(submitLabel || "Зберегти");
        }

        cancelBtn?.addEventListener("click", () => {
            if (typeof Modal.hide === "function") {
                Modal.hide();
            }
        });

        saveBtn?.addEventListener("click", async () => {
            const name = String(nameInput?.value || "").trim();
            const position = String(positionInput?.value || "").trim();

            if (!name) {
                notify("Вкажіть ім’я та прізвище", "error", 3500);
                nameInput?.focus();
                return;
            }

            saveBtn.disabled = true;
            try {
                await onSubmit({ name, position });
                if (typeof Modal.hide === "function") {
                    Modal.hide();
                }
            } finally {
                saveBtn.disabled = false;
            }
        });
    }

    function openDeleteModal({ name = "" }, onConfirm) {
        const modalHtml = getTemplateHtml("settings-delete-modal-template");
        if (
            typeof Modal === "undefined" ||
            typeof Modal.show !== "function" ||
            !modalHtml
        ) {
            const ok = window.confirm("Видалити цю картку?");
            if (!ok) return;
            onConfirm();
            return;
        }

        const title = "Видалити картку";
        const message = name
            ? `Ви дійсно хочете видалити картку «${name}»?`
            : "Ви дійсно хочете видалити цю картку?";

        Modal.show(modalHtml, "info", title, "");

        const content = document.querySelector(
            "#modal-container .modal-content",
        );
        if (!content) {
            return;
        }

        const cancelBtn = content.querySelector(
            "#settings-delete-modal-cancel",
        );
        const confirmBtn = content.querySelector(
            "#settings-delete-modal-confirm",
        );
        const confirmText = content.querySelector('[data-role="confirm-text"]');

        if (confirmText) {
            confirmText.textContent = message;
        }

        cancelBtn?.addEventListener("click", () => {
            if (typeof Modal.hide === "function") {
                Modal.hide();
            }
        });

        confirmBtn?.addEventListener("click", async () => {
            confirmBtn.disabled = true;
            try {
                await onConfirm();
                if (typeof Modal.hide === "function") {
                    Modal.hide();
                }
            } finally {
                confirmBtn.disabled = false;
            }
        });
    }

    root.querySelectorAll(".settings-doc__save-btn").forEach((btn) => {
        btn.addEventListener("click", async () => {
            const fieldKey = btn.dataset.fieldKey || "";
            const fieldInput = document.getElementById(
                btn.dataset.fieldInput || "",
            );
            if (!fieldKey || !fieldInput) return;

            btn.disabled = true;
            try {
                await requestJson(api.saveField, {
                    method: "POST",
                    body: {
                        key: fieldKey,
                        value: fieldInput.value || "",
                    },
                });
                notify("Збережено");
            } catch (error) {
                notify(error.message, "error", 4500);
            } finally {
                btn.disabled = false;
            }
        });
    });

    const currentYearBtn = document.getElementById("settings-set-current-year");
    currentYearBtn?.addEventListener("click", () => {
        const yearEl = document.getElementById("settings-document-year");
        if (yearEl) {
            yearEl.value = String(new Date().getFullYear());
        }
    });

    root.addEventListener("click", async (event) => {
        const actionButton = event.target.closest("[data-action]");
        if (!actionButton) return;

        const action = actionButton.dataset.action;
        const card = actionButton.closest(".settings-person");
        const addButton = actionButton.closest(".settings-person__add-button");

        const roleKey =
            addButton?.dataset.roleKey || card?.dataset.roleKey || "";
        const personId = card?.dataset.personId || "";

        if (!roleKey) return;

        try {
            if (action === "add") {
                openPersonModal(
                    {
                        title: "Додати особу",
                        submitLabel: "Додати",
                        initialName: "",
                        initialPosition: "",
                    },
                    async ({ name, position }) => {
                        await requestJson(api.addPerson, {
                            method: "POST",
                            body: {
                                role_key: roleKey,
                                name,
                                position,
                            },
                        });

                        notify("Картку додано");
                        hardReload();
                    },
                );
                return;
            }

            if (!personId) return;

            if (action === "edit") {
                const initialName =
                    card?.dataset.personName ||
                    card?.querySelector(".settings-person__name")
                        ?.textContent ||
                    "";
                const initialPosition =
                    card?.dataset.personPosition ||
                    card?.querySelector(".settings-person__position")
                        ?.textContent ||
                    "";

                openPersonModal(
                    {
                        title: "Редагувати особу",
                        submitLabel: "Зберегти",
                        initialName: String(initialName).trim(),
                        initialPosition: String(initialPosition).trim(),
                    },
                    async ({ name, position }) => {
                        await requestJson(api.updatePerson, {
                            method: "POST",
                            body: {
                                role_key: roleKey,
                                person_id: personId,
                                name,
                                position,
                            },
                        });

                        notify("Картку оновлено");
                        hardReload();
                    },
                );
                return;
            }

            if (action === "delete") {
                const personName =
                    card?.dataset.personName ||
                    card?.querySelector(".settings-person__name")
                        ?.textContent ||
                    "";

                openDeleteModal(
                    {
                        name: String(personName).trim(),
                    },
                    async () => {
                        await requestJson(api.deletePerson, {
                            method: "POST",
                            body: {
                                role_key: roleKey,
                                person_id: personId,
                            },
                        });

                        notify("Картку видалено");
                        hardReload();
                    },
                );
                return;
            }

            if (action === "select") {
                await requestJson(api.selectPerson, {
                    method: "POST",
                    body: {
                        role_key: roleKey,
                        person_id: personId,
                    },
                });

                notify("Вибір збережено");
                hardReload();
            }
        } catch (error) {
            notify(error.message, "error", 4500);
        }
    });

    setActiveTab("doc-template");
});
