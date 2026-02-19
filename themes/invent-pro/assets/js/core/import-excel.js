/**
 * import-excel.js
 * -----------------
 * Скрипт для компонента импорта Excel
 * Функции:
 * - запуск выбора файла
 * - отображение прогресса прямо в кнопке
 * - отправка файла через Winter CMS AJAX
 * - применение выбранных различий в модальном окне
 */

document.addEventListener("DOMContentLoaded", () => {
    const importForm = document.getElementById("importForm");
    const importInput = document.getElementById("importInput");
    const importButton = document.getElementById("importButton");

    if (!importForm || !importInput || !importButton) return;

    // Инициализация прогресса на кнопке
    const btnProgress = new ButtonProgress(importButton);
    let importHeaderAnimTimer = null;
    let importTabButtons = [];
    let importTabSections = [];
    let importActiveTabIndex = 0;
    let importHasTabsFlow = false;
    let importActivateTab = null;
    let importIsLastTab = () => true;
    let importTableScrollBinding = null;

    function clearImportModalScrollStickyBinding() {
        if (!importTableScrollBinding) {
            return;
        }

        const { host, handler } = importTableScrollBinding;
        if (host && handler) {
            host.removeEventListener("scroll", handler);
        }
        importTableScrollBinding = null;
    }

    function updateImportTableHeaderStickyState() {
        if (typeof Modal === "undefined" || !Modal.content) {
            return;
        }

        const activeSection = Modal.content.querySelector(
            ".import-modal-section:not(.d-none)",
        );
        const headers = Array.from(
            Modal.content.querySelectorAll(
                ".import-modal-section .table-title",
            ),
        );

        headers.forEach((header) => {
            const isVisible = activeSection && activeSection.contains(header);
            if (!isVisible) {
                header.classList.remove("scrolled");
                return;
            }

            header.classList.toggle("scrolled", Modal.content.scrollTop > 0);
        });
    }

    function bindImportModalScrollSticky() {
        if (typeof Modal === "undefined" || !Modal.content) {
            return;
        }

        clearImportModalScrollStickyBinding();

        const host = Modal.content;
        const handler = () => updateImportTableHeaderStickyState();
        host.addEventListener("scroll", handler, { passive: true });

        importTableScrollBinding = { host, handler };
        updateImportTableHeaderStickyState();
    }

    function animateModalHeaderChange(nextTitle, nextSubtitle) {
        if (typeof Modal === "undefined" || !Modal.window) {
            return;
        }

        const titleEl = Modal.title;
        const subtitleEl = Modal.subtitle;
        const textWrap = Modal.window.querySelector(".modal-header__text");

        const updateHeaderText = () => {
            if (titleEl) {
                titleEl.textContent = nextTitle;
            }
            if (subtitleEl) {
                subtitleEl.textContent = nextSubtitle;
            }
        };

        const currentTitle = titleEl ? titleEl.textContent : "";
        const currentSubtitle = subtitleEl ? subtitleEl.textContent : "";
        if (currentTitle === nextTitle && currentSubtitle === nextSubtitle) {
            return;
        }

        if (!textWrap) {
            updateHeaderText();
            return;
        }

        if (importHeaderAnimTimer) {
            clearTimeout(importHeaderAnimTimer);
            importHeaderAnimTimer = null;
        }

        textWrap.classList.remove("is-switching-in");
        textWrap.classList.add("is-switching-out");

        importHeaderAnimTimer = setTimeout(() => {
            updateHeaderText();
            textWrap.classList.remove("is-switching-out");
            textWrap.classList.add("is-switching-in");

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    textWrap.classList.remove("is-switching-in");
                });
            });

            importHeaderAnimTimer = null;
        }, 130);
    }

    function teardownImportModalUiBehavior() {
        clearImportModalScrollStickyBinding();
        importTabButtons = [];
        importTabSections = [];
        importActiveTabIndex = 0;
        importHasTabsFlow = false;
        importActivateTab = null;
        importIsLastTab = () => true;
        if (importHeaderAnimTimer) {
            clearTimeout(importHeaderAnimTimer);
            importHeaderAnimTimer = null;
        }

        if (typeof Modal !== "undefined" && Modal.window) {
            Modal.window.classList.remove("modal-window--import");
            Modal.window.style.width = "";
            Modal.window.style.maxWidth = "";
            if (Modal.content) {
                delete Modal.content.dataset.importUiReady;
            }

            const textWrap = Modal.window.querySelector(".modal-header__text");
            if (textWrap) {
                textWrap.classList.remove("is-switching-out");
                textWrap.classList.remove("is-switching-in");
            }
        }
    }

    function setupImportModalUiBehavior() {
        if (typeof Modal === "undefined" || !Modal.window || !Modal.content) {
            return;
        }

        const importMarker = Modal.content.querySelector(
            "#import-download-report",
        );
        if (!importMarker) {
            teardownImportModalUiBehavior();
            return;
        }

        if (Modal.content.dataset.importUiReady === "1") {
            bindImportModalScrollSticky();
            return;
        }

        Modal.window.classList.add("modal-window--import");
        Modal.window.style.width = "min(920px, calc(100vw - 48px))";
        Modal.window.style.maxWidth = "calc(100vw - 48px)";

        const sections = Array.from(
            Modal.content.querySelectorAll(".import-modal-section"),
        );
        if (!sections.length) {
            return;
        }

        const tabsRoot = Modal.content.querySelector("#import-tabs");
        const primaryButton = Modal.content.querySelector("#apply-differences");
        const nextLabel =
            primaryButton && primaryButton.dataset
                ? primaryButton.dataset.nextLabel || "Далі"
                : "Далі";
        const finalLabel =
            primaryButton && primaryButton.dataset
                ? primaryButton.dataset.finalLabel || "Зберегти зміни"
                : "Зберегти зміни";

        const setPrimaryButtonLabel = () => {
            if (!primaryButton) return;
            const isLast = importIsLastTab();
            primaryButton.textContent = isLast ? finalLabel : nextLabel;
            primaryButton.dataset.mode = isLast ? "apply" : "next";
        };

        const activateByIndex = (index, smooth = false) => {
            if (!importTabButtons.length || !importTabSections.length) {
                return;
            }

            const safeIndex = Math.max(
                0,
                Math.min(index, importTabButtons.length - 1),
            );
            importActiveTabIndex = safeIndex;

            const activeBtn = importTabButtons[safeIndex];
            const targetKey =
                activeBtn && activeBtn.dataset
                    ? activeBtn.dataset.tabTarget
                    : null;

            importTabButtons.forEach((btn, btnIndex) => {
                const isActive = btnIndex === safeIndex;
                btn.classList.toggle("is-active", isActive);
            });

            importTabSections.forEach((section) => {
                const isTarget = section.dataset.tabKey === targetKey;
                section.classList.toggle("d-none", !isTarget);
            });

            if (smooth && Modal.content) {
                Modal.content.scrollTo({ top: 0, behavior: "smooth" });
            }

            animateModalHeaderChange(
                activeBtn && activeBtn.dataset
                    ? activeBtn.dataset.tabTitle || "Результати імпорту"
                    : "Результати імпорту",
                activeBtn && activeBtn.dataset
                    ? activeBtn.dataset.tabSubtitle || ""
                    : "",
            );

            setPrimaryButtonLabel();
            bindImportModalScrollSticky();
        };

        teardownImportModalUiBehavior();

        importTabSections = sections;

        if (!tabsRoot) {
            importHasTabsFlow = false;
            importIsLastTab = () => true;
            const first = sections[0];
            if (first) {
                animateModalHeaderChange(
                    first.dataset.modalTitle || "Результати імпорту",
                    first.dataset.modalSubtitle || "",
                );
            }
            setPrimaryButtonLabel();
            bindImportModalScrollSticky();
            return;
        }

        importTabButtons = Array.from(
            tabsRoot.querySelectorAll(".import-tab-btn[data-tab-target]"),
        );

        if (!importTabButtons.length) {
            importHasTabsFlow = false;
            importIsLastTab = () => true;
            setPrimaryButtonLabel();
            bindImportModalScrollSticky();
            return;
        }

        importHasTabsFlow = importTabButtons.length > 1;
        importIsLastTab = () =>
            importActiveTabIndex >= importTabButtons.length - 1;
        importActivateTab = (index, smooth = false) =>
            activateByIndex(index, smooth);

        const preselectedIndex = importTabButtons.findIndex((btn) =>
            btn.classList.contains("is-active"),
        );
        activateByIndex(preselectedIndex >= 0 ? preselectedIndex : 0, false);

        Modal.content.dataset.importUiReady = "1";
    }

    // -----------------------------
    // Универсальный обработчик ответа сервера
    // -----------------------------
    function handleServerResponse(data) {
        if (window.handleServerResponse) {
            window.handleServerResponse(data);
        }
    }

    // -----------------------------
    // Кнопка "Импорт" — открывает диалог выбора файла
    // -----------------------------
    importButton.addEventListener("click", () => importInput.click());

    // -----------------------------
    // Авто-отправка формы при выборе файла
    // -----------------------------
    importInput.addEventListener("change", () => {
        if (!importInput.files.length) return;

        console.log("Файл выбран:", importInput.files[0]);
        btnProgress.start();

        // Имитируем прогресс до получения ответа сервера
        let fakeProgress = 0;
        const interval = setInterval(() => {
            if (fakeProgress < 90) {
                fakeProgress += Math.random() * 5;
                btnProgress.update(Math.floor(fakeProgress));
            } else {
                clearInterval(interval);
            }
        }, 200);

        // Winter CMS AJAX вызов с файлом
        $(importForm).request(importForm.getAttribute("data-request"), {
            files: importInput, // Winter CMS видит input[name] через data-request-files
            beforeSend() {
                btnProgress.start();
            },
            success(data) {
                clearInterval(interval);
                btnProgress.update(100);
                handleServerResponse(data);
            },
            error(err) {
                clearInterval(interval);
                btnProgress.finish();
                console.error("Ошибка AJAX:", err);
                toast("Ошибка импорта", "error");
            },
            complete() {
                importInput.value = ""; // сброс input
                btnProgress.finish();
            },
        });
    });

    function updateSelectAllState() {
        const selectAll = document.getElementById("select-all-diffs");
        const allDiffCheckboxes = Array.from(
            document.querySelectorAll(".diff-checkbox"),
        );

        if (!selectAll || !allDiffCheckboxes.length) return;

        const checkedCount = allDiffCheckboxes.filter(
            (cb) => cb.checked,
        ).length;

        selectAll.checked =
            checkedCount > 0 && checkedCount === allDiffCheckboxes.length;
        selectAll.indeterminate =
            checkedCount > 0 && checkedCount < allDiffCheckboxes.length;
    }

    function collectSelectedUpdates() {
        const selectedCheckboxes = Array.from(
            document.querySelectorAll(".diff-checkbox:checked"),
        );

        const updates = {};

        selectedCheckboxes.forEach((checkbox) => {
            const row = checkbox.closest(".diff-row");
            const productId = checkbox.dataset.id;
            if (!row || !productId) return;

            const rowUpdates = {};
            row.querySelectorAll(
                'input[type="hidden"][name^="updates["]',
            ).forEach((hiddenInput) => {
                const match = hiddenInput.name.match(
                    /^updates\[(.+?)\]\[(.+?)\]$/,
                );
                if (!match) return;
                const field = match[2];
                rowUpdates[field] = hiddenInput.value;
            });

            if (!rowUpdates.inv_number) {
                rowUpdates.inv_number = checkbox.value;
            }

            updates[productId] = rowUpdates;
        });

        return updates;
    }

    let pendingApplyUpdates = null;
    let pendingApplyReport = null;

    function closeApplyConfirmLayer() {
        const existingLayer = document.getElementById(
            "import-apply-confirm-layer",
        );
        if (existingLayer) {
            existingLayer.remove();
        }
    }

    function showApplyConfirmModal(selectedCount, updates, report) {
        pendingApplyUpdates = updates;
        pendingApplyReport = report;
        closeApplyConfirmLayer();

        const layer = document.createElement("div");
        layer.id = "import-apply-confirm-layer";
        layer.style.position = "absolute";
        layer.style.inset = "0";
        layer.style.display = "flex";
        layer.style.alignItems = "center";
        layer.style.justifyContent = "center";
        layer.style.padding = "24px";
        layer.style.background = "rgba(255,255,255,0.72)";
        layer.style.backdropFilter = "blur(4px)";
        layer.style.zIndex = "50";

        layer.innerHTML = `
            <div style="width:min(420px,100%);background:var(--bg-box);border-radius:var(--radius-lg);box-shadow: 0 0 16px rgba(0,0,0,0.1);padding:24px;display:flex;flex-direction:column;gap:16px;">
                <div style="font-size:20px;font-weight:600;color:var(--text-primary);line-height:1;">Підтвердження змін</div>
                <div style="font-size:14px;color:var(--text-secondary);line-height:1.4;">Застосувати зміни для <b>${selectedCount}</b> позицій?</div>
                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <button type="button" class="button button--nm button--secondary" id="cancel-apply-confirm">Скасувати</button>
                    <button type="button" class="button button--nm button--brand" id="confirm-apply-confirm">Підтвердити</button>
                </div>
            </div>
        `;

        layer.addEventListener("click", (event) => {
            if (event.target === layer) {
                closeApplyConfirmLayer();
            }
        });

        Modal.window.appendChild(layer);
    }

    function applyDifferences(updates, report) {
        $.request("onApplyDifferences", {
            data: { updates, report },
            beforeSend() {
                btnProgress.start();
            },
            success(data) {
                btnProgress.update(100);
                handleServerResponse(data);
                Modal.hide();
                teardownImportModalUiBehavior();
            },
            error(err) {
                console.error("Ошибка AJAX при применении различий:", err);
                toast("Произошла ошибка при отправке данных", "error");
                closeApplyConfirmLayer();
                btnProgress.finish();
            },
            complete() {
                btnProgress.finish();
            },
        });
    }

    function collectDifferencesForDownload() {
        const rows = Array.from(document.querySelectorAll(".diff-row"));

        const getText = (row, selector) => {
            const el = row.querySelector(selector);
            return el && typeof el.textContent === "string"
                ? el.textContent.trim()
                : "";
        };

        const getData = (row, key) => {
            if (!row || !row.dataset) {
                return "";
            }
            return row.dataset[key] || "";
        };

        return rows
            .map((row) => {
                const checkbox = row.querySelector(".diff-checkbox");
                if (!checkbox) return null;

                return {
                    id: checkbox.dataset.id || "",
                    inv_number: checkbox.value || "",
                    name:
                        getData(row, "name") ||
                        getText(row, ".warehouse__name") ||
                        getText(row, ".import__name"),
                    current_quantity:
                        getData(row, "currentQuantity") ||
                        getText(row, ".import__quantity"),
                    excel_quantity:
                        getData(row, "excelQuantity") ||
                        getText(row, ".import__quantity-excel"),
                    current_price:
                        getData(row, "currentPrice") ||
                        getText(row, ".import__price"),
                    excel_price:
                        getData(row, "excelPrice") ||
                        getText(row, ".import__price-excel"),
                    current_sum:
                        getData(row, "currentSum") ||
                        getText(row, ".import__sum"),
                    excel_sum:
                        getData(row, "excelSum") ||
                        getText(row, ".import__sum-excel"),
                };
            })
            .filter(Boolean);
    }

    function collectImportReportForDownload() {
        const input = document.getElementById("import-download-report");
        const encoded = input ? input.value || "" : "";

        if (!encoded) {
            const rows = collectDifferencesForDownload();
            return rows.length ? { differences: rows } : null;
        }

        try {
            const binary = atob(encoded);
            const bytes = Uint8Array.from(binary, (char) => char.charCodeAt(0));
            const json = new TextDecoder("utf-8").decode(bytes);
            const parsed = JSON.parse(json);

            if (!parsed || typeof parsed !== "object") {
                return null;
            }

            return {
                differences: Array.isArray(parsed.differences)
                    ? parsed.differences
                    : [],
                new_products: Array.isArray(parsed.new_products)
                    ? parsed.new_products
                    : [],
                missing_products: Array.isArray(parsed.missing_products)
                    ? parsed.missing_products
                    : [],
                ambiguous_matches: Array.isArray(parsed.ambiguous_matches)
                    ? parsed.ambiguous_matches
                    : [],
                split_candidates: Array.isArray(parsed.split_candidates)
                    ? parsed.split_candidates
                    : [],
                inv_sync_rows: Array.isArray(parsed.inv_sync_rows)
                    ? parsed.inv_sync_rows
                    : [],
            };
        } catch (error) {
            console.error("Не удалось распарсить отчет импорта:", error);
            const rows = collectDifferencesForDownload();
            return rows.length ? { differences: rows } : null;
        }
    }

    function collectAmbiguousResolutions() {
        const rows = Array.from(document.querySelectorAll(".ambiguous-item"));

        return rows
            .map((row) => {
                const selected = row.querySelector(".ambiguous-choice:checked");
                if (!selected) return null;

                return {
                    product_id:
                        selected.dataset.productId || selected.value || "",
                    product_name: selected.dataset.productName || "",
                    product_inv_number: selected.dataset.productInv || "",
                    excel_name: row.dataset.excelName || "",
                    excel_inv_number: row.dataset.excelInv || "",
                    excel_quantity: row.dataset.excelQuantity || "0",
                    excel_price: row.dataset.excelPrice || "0",
                    excel_sum: row.dataset.excelSum || "0",
                };
            })
            .filter(Boolean);
    }

    function collectSplitResolutions() {
        const selects = Array.from(
            document.querySelectorAll(".split-operation-select"),
        );

        return selects
            .map((select) => {
                const operationId = parseInt(select.value || "0", 10);
                const baseProductId = parseInt(
                    select.dataset.baseProductId || "0",
                    10,
                );
                const excelInv = select.dataset.excelInv || "";

                if (!operationId || !baseProductId || !excelInv) {
                    return null;
                }

                return {
                    base_product_id: baseProductId,
                    excel_inv_number: excelInv,
                    operation_id: operationId,
                };
            })
            .filter(Boolean);
    }

    function parseReportNumber(value) {
        if (value === null || value === undefined || value === "") {
            return 0;
        }

        const normalized = String(value).replace(/\s|\u00A0|\u202F/g, "").replace(",", ".");
        const parsed = Number.parseFloat(normalized);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function getActionableMissingProductsCount(report) {
        const rows = report && Array.isArray(report.missing_products)
            ? report.missing_products
            : [];

        return rows.reduce((count, row) => {
            const qty = Math.abs(parseReportNumber(row && row.current_quantity));
            const sum = Math.abs(parseReportNumber(row && row.current_sum));
            return qty > 0.0001 || sum > 0.0001 ? count + 1 : count;
        }, 0);
    }

    function validateSplitSelections(showToast = true) {
        const selects = Array.from(
            document.querySelectorAll(".split-operation-select"),
        );

        if (!selects.length) {
            return true;
        }

        let hasErrors = false;
        const usedByBase = new Map();

        selects.forEach((select) => {
            const container = select.closest(".split-item");
            const baseId = select.dataset.baseProductId || "";
            const selectedOperationId = parseInt(select.value || "0", 10);

            if (!selectedOperationId) {
                hasErrors = true;
                if (container) {
                    container.style.outline = "2px solid var(--warning, #f59e0b)";
                    container.style.outlineOffset = "2px";
                }
                return;
            }

            if (!usedByBase.has(baseId)) {
                usedByBase.set(baseId, new Set());
            }
            const used = usedByBase.get(baseId);

            if (used.has(selectedOperationId)) {
                hasErrors = true;
                if (container) {
                    container.style.outline = "2px solid var(--warning, #f59e0b)";
                    container.style.outlineOffset = "2px";
                }
                return;
            }

            used.add(selectedOperationId);
            if (container) {
                container.style.outline = "";
                container.style.outlineOffset = "";
            }
        });

        if (hasErrors && showToast) {
            toast(
                "Оберіть унікальні операції для кожної нової позиції в розподілі",
                "error",
            );
        }

        return !hasErrors;
    }

    function updateSplitOperationPreview(selectEl) {
        if (!selectEl) return;

        const row = selectEl.closest(".split-item");
        if (!row) return;

        const docCell = row.querySelector(".split-operation-doc");
        if (!docCell) return;

        const option = selectEl.options[selectEl.selectedIndex];
        if (!option || !selectEl.value) {
            docCell.textContent = "—";
            return;
        }

        const docNum = option.dataset.opDoc || "без №";
        const docDate = option.dataset.opDate || "без дати";
        docCell.textContent = `${docNum} • ${docDate}`;
    }

    function setAmbiguousRowHighlight(row, highlighted) {
        if (!row) return;

        if (highlighted) {
            row.classList.add("ambiguous-item--attention");
            row.style.outline = "2px solid var(--warning, #f59e0b)";
            row.style.outlineOffset = "2px";
            row.style.background = "rgba(245, 158, 11, 0.08)";
            return;
        }

        row.classList.remove("ambiguous-item--attention");
        row.style.outline = "";
        row.style.outlineOffset = "";
        row.style.background = "";
    }

    function validateAmbiguousSelections(showToast = true) {
        const rows = Array.from(document.querySelectorAll(".ambiguous-item"));
        let missingCount = 0;

        rows.forEach((row) => {
            const hasCandidates = !!row.querySelector(".ambiguous-choice");
            if (!hasCandidates) {
                setAmbiguousRowHighlight(row, false);
                return;
            }

            const selected = row.querySelector(".ambiguous-choice:checked");
            const missing = !selected;
            setAmbiguousRowHighlight(row, missing);
            if (missing) {
                missingCount += 1;
            }
        });

        if (missingCount > 0) {
            if (showToast) {
                toast(
                    `Оберіть відповідність для ${missingCount} неоднозначних позицій`,
                    "error",
                );
            }
            return false;
        }

        return true;
    }

    function downloadBase64File(base64, fileName, mimeType) {
        const binaryString = atob(base64);
        const length = binaryString.length;
        const bytes = new Uint8Array(length);

        for (let i = 0; i < length; i += 1) {
            bytes[i] = binaryString.charCodeAt(i);
        }

        const blob = new Blob([bytes], {
            type:
                mimeType ||
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");

        link.href = url;
        link.download =
            fileName ||
            `import-differences-${new Date().toISOString().slice(0, 10)}.xlsx`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    }

    // -----------------------------
    // Обработка кликов/изменений в модалке различий
    // -----------------------------
    document.addEventListener("change", function (event) {
        if (event.target && event.target.id === "select-all-diffs") {
            const checked = event.target.checked;
            document
                .querySelectorAll(".diff-checkbox")
                .forEach((cb) => (cb.checked = checked));
            updateSelectAllState();
            return;
        }

        if (event.target && event.target.classList.contains("diff-checkbox")) {
            updateSelectAllState();
        }

        if (
            event.target &&
            event.target.classList.contains("ambiguous-choice")
        ) {
            const row = event.target.closest(".ambiguous-item");
            setAmbiguousRowHighlight(row, false);
            return;
        }

        if (
            event.target &&
            event.target.classList.contains("split-operation-select")
        ) {
            updateSplitOperationPreview(event.target);
            validateSplitSelections(false);
        }
    });

    document.addEventListener("click", function (event) {
        const eventTarget = event.target;
        const tabButton =
            eventTarget && typeof eventTarget.closest === "function"
                ? eventTarget.closest(".import-tab-btn[data-tab-target]")
                : null;
        if (tabButton && importActivateTab && importTabButtons.length) {
            const idx = importTabButtons.indexOf(tabButton);
            if (idx >= 0) {
                importActivateTab(idx, false);
            }
            return;
        }

        if (event.target && event.target.id === "cancel-apply-confirm") {
            closeApplyConfirmLayer();
            return;
        }

        if (event.target && event.target.id === "confirm-apply-confirm") {
            const updates = pendingApplyUpdates;
            const report = pendingApplyReport;
            pendingApplyUpdates = null;
            pendingApplyReport = null;

            if (
                report &&
                Array.isArray(report.split_candidates) &&
                report.split_candidates.length > 0 &&
                !validateSplitSelections(true)
            ) {
                return;
            }

            closeApplyConfirmLayer();

            const hasUpdates = updates && Object.keys(updates).length > 0;
            const hasNewProducts =
                report && Array.isArray(report.new_products)
                    ? report.new_products.length > 0
                    : false;
            const actionableMissingProductsCount =
                getActionableMissingProductsCount(report);
            const hasActionableMissingProducts =
                actionableMissingProductsCount > 0;
            const hasAmbiguousResolutions =
                report && Array.isArray(report.ambiguous_resolutions)
                    ? report.ambiguous_resolutions.length > 0
                    : false;
            const hasSplitResolutions =
                report && Array.isArray(report.split_resolutions)
                    ? report.split_resolutions.length > 0
                    : false;
            const hasInvSyncRows =
                report && Array.isArray(report.inv_sync_rows)
                    ? report.inv_sync_rows.length > 0
                    : false;

            if (
                !hasUpdates &&
                !hasNewProducts &&
                !hasActionableMissingProducts &&
                !hasAmbiguousResolutions &&
                !hasSplitResolutions &&
                !hasInvSyncRows
            ) {
                toast("Немає вибраних змін", "error");
                return;
            }

            applyDifferences(updates || {}, report || null);
            return;
        }

        if (event.target && event.target.id === "cancel-differences") {
            Modal.hide();
            teardownImportModalUiBehavior();
            return;
        }

        if (event.target && event.target.id === "download-differences") {
            const report = collectImportReportForDownload();

            if (
                !report ||
                (!(report.differences && report.differences.length) &&
                    !(report.new_products && report.new_products.length) &&
                    !(
                        report.missing_products &&
                        report.missing_products.length
                    ) &&
                    !(
                        report.ambiguous_matches &&
                        report.ambiguous_matches.length
                    ) &&
                    !(
                        report.split_candidates &&
                        report.split_candidates.length
                    ))
            ) {
                toast("Немає відмінностей для експорту", "info");
                return;
            }

            let reportPayload = "";
            try {
                reportPayload = JSON.stringify(report);
            } catch (serializationError) {
                console.error(
                    "Не удалось сериализовать отчет импорта для экспорта:",
                    serializationError,
                );
            }

            $.request("onDownloadDifferencesExcel", {
                data: reportPayload ? { report: reportPayload } : { report },
                beforeSend() {
                    btnProgress.start();
                },
                success(data) {
                    btnProgress.update(100);

                    if (data && data.download && data.download.content) {
                        downloadBase64File(
                            data.download.content,
                            data.download.filename,
                            data.download.mime,
                        );
                    }

                    handleServerResponse(data);
                },
                error(err) {
                    console.error("Ошибка AJAX при экспорте различий:", err);
                    toast("Не вдалося завантажити файл відмінностей", "error");
                },
                complete() {
                    btnProgress.finish();
                },
            });
            return;
        }

        if (event.target && event.target.id === "apply-differences") {
            const mode = event.target.dataset.mode || "apply";

            if (mode === "next") {
                if (importActivateTab) {
                    importActivateTab(importActiveTabIndex + 1, true);
                }
                return;
            }

            if (importHasTabsFlow && importActivateTab && !importIsLastTab()) {
                importActivateTab(importActiveTabIndex + 1, true);
                return;
            }

            const updates = collectSelectedUpdates();
            const report = collectImportReportForDownload();
            const ambiguousResolutions = collectAmbiguousResolutions();
            const splitResolutions = collectSplitResolutions();

            if (!validateAmbiguousSelections(true)) {
                return;
            }

            const hasSplitCandidates = Array.isArray(
                report && report.split_candidates,
            )
                ? report.split_candidates.length > 0
                : false;

            if (hasSplitCandidates && !validateSplitSelections(true)) {
                return;
            }

            if (report) {
                report.ambiguous_resolutions = ambiguousResolutions;
                report.split_resolutions = splitResolutions;
            }

            const newProductsCount = Array.isArray(
                report && report.new_products,
            )
                ? report.new_products.length
                : 0;
            const missingProductsCount = getActionableMissingProductsCount(report);
            const selectedDiffsCount = Object.keys(updates).length;
            const selectedAmbiguousCount = ambiguousResolutions.length;
            const selectedSplitCount = splitResolutions.length;
            const totalToApply =
                selectedDiffsCount +
                newProductsCount +
                missingProductsCount +
                selectedAmbiguousCount +
                selectedSplitCount;

            if (totalToApply === 0) {
                toast("Выберите хотя бы один продукт", "error");
                return;
            }

            showApplyConfirmModal(totalToApply, updates, report);
        }
    });

    const modalContent = document.querySelector(
        "#modal-container .modal-content",
    );
    if (modalContent && typeof MutationObserver !== "undefined") {
        const observer = new MutationObserver(() => {
            if (modalContent.querySelector("#select-all-diffs")) {
                updateSelectAllState();
            }

            const hasImportModal = !!modalContent.querySelector(
                "#import-download-report",
            );

            if (hasImportModal && modalContent.dataset.importUiReady !== "1") {
                setupImportModalUiBehavior();
            }

            if (!hasImportModal && modalContent.dataset.importUiReady === "1") {
                teardownImportModalUiBehavior();
            }
        });

        observer.observe(modalContent, {
            childList: true,
            subtree: true,
        });
    }
});
