document.addEventListener("DOMContentLoaded", () => {
    const root = document.querySelector(".doc-builder");
    if (!root) return;

    const api = {
        templates: "/api/operation-doc-templates",
        generate: (operationId) =>
            `/api/operations/${operationId}/generate-doc`,
        status: (taskId) => `/api/operations/doc-generation-status/${taskId}`,
    };

    const receiverEl = document.getElementById("doc-builder-receiver");
    const commissionHeadEl = document.getElementById(
        "doc-builder-commission-head",
    );
    const member1El = document.getElementById(
        "doc-builder-commission-member-1",
    );
    const member2El = document.getElementById(
        "doc-builder-commission-member-2",
    );
    const member3El = document.getElementById(
        "doc-builder-commission-member-3",
    );
    const responsibleEl = document.getElementById("doc-builder-responsible");

    const docsListEl = document.getElementById("doc-builder-doc-list");
    const writeoffSubtypeInputs = Array.from(
        document.querySelectorAll('input[name="doc_builder_writeoff_subtype"]'),
    );
    const resetBtn = document.getElementById("doc-builder-reset");
    const generateBtn = document.getElementById("doc-builder-generate");

    const operationId = Number(root.dataset.operationId || 0);
    const hasMissingSettings =
        String(root.dataset.hasMissingSettings || "0") === "1";

    let missingItems = [];
    try {
        missingItems = JSON.parse(root.dataset.missingItems || "[]");
        if (!Array.isArray(missingItems)) {
            missingItems = [];
        }
    } catch (error) {
        missingItems = [];
    }

    let selectedDocs = [];
    let templates = [];
    let fallbackTemplateId = "default";

    let context = {
        kind: "transfer",
        writeoffSubtype: "autoparts",
    };
    const defaultWriteoffSubtype = "autoparts";

    const customSelectControls = new Map();

    function getTemplateHtml(templateId) {
        const template = document.getElementById(templateId);
        if (!template) return "";

        if (template.tagName === "TEMPLATE") {
            return template.innerHTML;
        }

        return template.innerHTML || "";
    }

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

    function normalizeTemplateName(value) {
        return String(value || "")
            .toLowerCase()
            .replace(/[^\p{L}\p{N}]+/gu, " ")
            .trim();
    }

    function buildTemplateCandidates(documentName, builderContext) {
        const candidates = [documentName];
        if (
            builderContext?.kind === "writeoff" &&
            builderContext?.writeoffSubtype === "autoparts"
        ) {
            candidates.unshift(
                `${documentName} автозапчастини`,
                `${documentName} (автозапчастини)`,
                `${documentName}_автозапчастини`,
            );
        }

        return candidates;
    }

    function resolveTemplateIdForDocument(documentName) {
        const fileTemplates = (templates || []).filter(
            (item) => item.type === "file",
        );
        const candidates = buildTemplateCandidates(documentName, context);

        for (const candidate of candidates) {
            const normalizedCandidate = normalizeTemplateName(candidate);
            const exact = fileTemplates.find(
                (template) =>
                    normalizeTemplateName(template.name) ===
                    normalizedCandidate,
            );
            if (exact?.id) return exact.id;
        }

        for (const candidate of candidates) {
            const normalizedCandidate = normalizeTemplateName(candidate);
            const partial = fileTemplates.find((template) => {
                const normalizedTemplate = normalizeTemplateName(template.name);
                return (
                    normalizedTemplate.includes(normalizedCandidate) ||
                    normalizedCandidate.includes(normalizedTemplate)
                );
            });
            if (partial?.id) return partial.id;
        }

        return fallbackTemplateId || "default";
    }

    async function pollGenerationTask(taskId) {
        const attempts = 60;
        for (let i = 0; i < attempts; i++) {
            await new Promise((resolve) => setTimeout(resolve, 2000));
            const data = await requestJson(api.status(taskId));
            if (data.status === "ready") return data;
            if (data.status === "error") {
                throw new Error(
                    data.error || data.message || "Помилка генерації",
                );
            }
        }

        throw new Error("Перевищено час очікування формування");
    }

    function parseFileNameFromHeaders(headers) {
        const disposition = headers.get("content-disposition") || "";
        const utf8Match = disposition.match(/filename\*=UTF-8''([^;]+)/i);
        if (utf8Match && utf8Match[1]) return decodeURIComponent(utf8Match[1]);

        const asciiMatch = disposition.match(/filename="?([^";]+)"?/i);
        if (asciiMatch && asciiMatch[1]) return asciiMatch[1];

        return null;
    }

    async function downloadByUrl(url, fallbackFileName) {
        const response = await fetch(url, {
            credentials: "same-origin",
            headers: {
                Accept: "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            },
        });

        if (!response.ok) {
            throw new Error("Не вдалося завантажити DOCX");
        }

        const fileName =
            parseFileNameFromHeaders(response.headers) || fallbackFileName;
        const blob = await response.blob();
        const objectUrl = URL.createObjectURL(blob);
        const anchor = document.createElement("a");
        anchor.href = objectUrl;
        anchor.download = fileName;
        document.body.appendChild(anchor);
        anchor.click();
        anchor.remove();
        URL.revokeObjectURL(objectUrl);
    }

    function collectSettings() {
        return {
            receiver_name: receiverEl?.value || "",
            commission_head: commissionHeadEl?.value || "",
            commission_member_1: member1El?.value || "",
            commission_member_2: member2El?.value || "",
            commission_member_3: member3El?.value || "",
            responsible_person: responsibleEl?.value || "",
        };
    }

    async function confirmBeforeGeneration() {
        const modalHtml = getTemplateHtml("doc-builder-warning-modal-template");

        if (
            typeof Modal === "undefined" ||
            typeof Modal.show !== "function" ||
            !modalHtml
        ) {
            const baseWarning =
                "Після формування документи потребують ручної корекції (відступи, переноси, форматування).";
            const missingWarning = hasMissingSettings
                ? "\nУ налаштуваннях формування є незаповнені дані."
                : "";

            return window.confirm(
                `${baseWarning}${missingWarning}\n\nПродовжити формування?`,
            );
        }

        Modal.show(modalHtml, "warning", "Попередження перед формуванням", "");

        return await new Promise((resolve) => {
            const content = document.querySelector(
                "#modal-container .modal-content",
            );
            if (!content) {
                resolve(false);
                return;
            }

            const cancelBtn = content.querySelector(
                "#doc-builder-warning-cancel",
            );
            const settingsBtn = content.querySelector(
                "#doc-builder-warning-settings",
            );
            const generateConfirmBtn = content.querySelector(
                "#doc-builder-warning-generate",
            );
            const missingSection = content.querySelector(
                "#doc-builder-warning-missing-section",
            );
            const missingLead = content.querySelector(
                "#doc-builder-warning-missing-lead",
            );
            const missingList = content.querySelector(
                "#doc-builder-warning-missing-list",
            );
            const missingHelp = content.querySelector(
                "#doc-builder-warning-missing-help",
            );
            const commonText = content.querySelector(
                "#doc-builder-warning-common-text",
            );

            if (commonText) {
                commonText.textContent =
                    "Важливо: після формування документи зазвичай потребують ручної корекції (відступи, переноси, форматування, а також виділені фрагменти під вашу ситуацію).";
            }

            if (hasMissingSettings && missingSection) {
                if (missingLead) {
                    missingLead.textContent =
                        "Увага: у налаштуваннях формування не заповнені деякі обов'язкові дані. Документи можуть бути сформовані некоректно.";
                }

                if (missingList) {
                    missingList.innerHTML = "";
                    missingItems.forEach((item) => {
                        const listItem = document.createElement("li");
                        listItem.textContent = String(item);
                        missingList.appendChild(listItem);
                    });
                }

                if (missingHelp) {
                    missingHelp.textContent =
                        "Ви можете виправити ці дані на сторінці налаштувань.";
                }
            } else if (missingSection) {
                missingSection.style.display = "none";
            }

            cancelBtn?.addEventListener("click", () => {
                if (typeof Modal.hide === "function") {
                    Modal.hide();
                }
                resolve(false);
            });

            settingsBtn?.addEventListener("click", () => {
                if (typeof Modal.hide === "function") {
                    Modal.hide();
                }
                window.open("/settings", "_blank", "noopener");
                resolve(false);
            });

            generateConfirmBtn?.addEventListener("click", () => {
                if (typeof Modal.hide === "function") {
                    Modal.hide();
                }
                resolve(true);
            });
        });
    }

    function closeAllCustomSelects() {
        customSelectControls.forEach((control) => {
            control.root.classList.remove("active");
        });
    }

    function syncSelectHighlight(inputEl) {
        if (!inputEl || !inputEl.id) return;

        const rootEl = document.getElementById(`${inputEl.id}-select`);
        if (!rootEl) return;

        const currentValue = String(inputEl.value || "");
        rootEl.querySelectorAll(".options .option").forEach((optionEl) => {
            const optionValue = String(optionEl.dataset.value || "");
            optionEl.classList.toggle(
                "is-selected",
                optionValue === currentValue,
            );
        });
    }

    function initCustomSelect(inputEl) {
        if (!inputEl || !inputEl.id) return;

        const rootEl = document.getElementById(`${inputEl.id}-select`);
        if (!rootEl) return;

        const selected = rootEl.querySelector(".selected");
        const options = rootEl.querySelector(".options");
        if (!selected || !options) return;

        customSelectControls.set(inputEl.id, {
            root: rootEl,
            selected,
            options,
        });

        syncSelectHighlight(inputEl);

        selected.addEventListener("click", (event) => {
            event.preventDefault();
            const isActive = rootEl.classList.contains("active");
            closeAllCustomSelects();
            if (!isActive) {
                rootEl.classList.add("active");
            }
        });

        options.addEventListener("click", (event) => {
            const option = event.target.closest(".option");
            if (!option) return;

            const value = String(option.dataset.value || "");
            inputEl.value = value;
            selected.textContent = value || "Оберіть зі списку";
            syncSelectHighlight(inputEl);
            rootEl.classList.remove("active");
            inputEl.dispatchEvent(new Event("change", { bubbles: true }));
        });
    }

    function syncSelectedDocsFromUI() {
        if (!docsListEl) return;

        selectedDocs = Array.from(
            docsListEl.querySelectorAll(".doc-builder__doc-checkbox:checked"),
        )
            .map((input) => String(input.value || "").trim())
            .filter((value) => value !== "");

        if (generateBtn) {
            generateBtn.disabled =
                selectedDocs.length === 0 || operationId <= 0;
        }
    }

    function applyWriteoffSubtypeToUI(value) {
        const normalized = value === "materials" ? "materials" : "autoparts";
        writeoffSubtypeInputs.forEach((input) => {
            input.checked = input.value === normalized;
        });
    }

    function setWriteoffSubtype(value) {
        context.writeoffSubtype =
            value === "materials" ? "materials" : "autoparts";
        applyWriteoffSubtypeToUI(context.writeoffSubtype);
    }

    async function generateDocument(docName, settings, action = "download") {
        const templateId = resolveTemplateIdForDocument(docName);

        let result = await requestJson(api.generate(operationId), {
            method: "POST",
            body: {
                action,
                template_id: templateId,
                document_name: docName,
                settings,
            },
        });

        if (result.queued && result.task_id) {
            result = await pollGenerationTask(result.task_id);
        }

        if (!result.download_url) {
            throw new Error(
                `Не вдалося отримати файл для документа: ${docName}`,
            );
        }

        return result.download_url;
    }

    async function initTemplates() {
        if (operationId <= 0) {
            return;
        }

        const templatesData = await requestJson(
            `${api.templates}?operation_id=${encodeURIComponent(operationId)}`,
        );
        templates = Array.isArray(templatesData.templates)
            ? templatesData.templates
            : [];
        fallbackTemplateId = templatesData.resolved_template_id || "default";
    }

    [
        receiverEl,
        commissionHeadEl,
        member1El,
        member2El,
        member3El,
        responsibleEl,
    ].forEach((control) => {
        initCustomSelect(control);
    });

    document.addEventListener("click", (event) => {
        if (!event.target.closest(".custom-select.doc-builder__select")) {
            closeAllCustomSelects();
        }
    });

    writeoffSubtypeInputs.forEach((input) => {
        input.addEventListener("change", () => {
            if (input.checked) {
                setWriteoffSubtype(input.value);
            }
        });
    });

    docsListEl
        ?.querySelectorAll(".doc-builder__doc-checkbox")
        .forEach((input) =>
            input.addEventListener("change", syncSelectedDocsFromUI),
        );

    resetBtn?.addEventListener("click", () => {
        [
            receiverEl,
            commissionHeadEl,
            member1El,
            member2El,
            member3El,
            responsibleEl,
        ].forEach((inputEl) => {
            if (!inputEl) return;

            const defaultValue = String(inputEl.dataset.default || "");
            inputEl.value = defaultValue;

            const control = customSelectControls.get(inputEl.id);
            if (control) {
                control.selected.textContent =
                    defaultValue || "Оберіть зі списку";
            }

            syncSelectHighlight(inputEl);
        });

        setWriteoffSubtype(defaultWriteoffSubtype);

        docsListEl
            ?.querySelectorAll(".doc-builder__doc-checkbox")
            .forEach((input) => {
                input.checked = true;
            });

        syncSelectedDocsFromUI();
    });

    generateBtn?.addEventListener("click", async () => {
        if (!selectedDocs.length || operationId <= 0) return;

        const confirmed = await confirmBeforeGeneration();
        if (!confirmed) {
            return;
        }

        const settings = collectSettings();

        generateBtn.disabled = true;
        resetBtn.disabled = true;

        try {
            for (const docName of selectedDocs) {
                const url = await generateDocument(docName, settings);
                await downloadByUrl(url, `${docName}.docx`);
            }

            if (typeof toast === "function") {
                toast("Документи сформовано", "success", 3500, "top-center");
            }
        } catch (error) {
            if (typeof toast === "function") {
                toast(error.message, "error", 5000, "top-center");
            }
        } finally {
            generateBtn.disabled = false;
            resetBtn.disabled = false;
        }
    });

    try {
        const builderState = JSON.parse(
            localStorage.getItem("docGenerationBuilderState") || "null",
        );
        context = {
            kind: builderState?.kind || "transfer",
            writeoffSubtype: builderState?.writeoffSubtype || "autoparts",
        };
    } catch (error) {
        context = {
            kind: "transfer",
            writeoffSubtype: "autoparts",
        };
    }

    setWriteoffSubtype(context.writeoffSubtype);
    syncSelectedDocsFromUI();

    initTemplates().catch((error) => {
        if (typeof toast === "function") {
            toast(error.message, "error", 5000, "top-center");
        }
    });
});
