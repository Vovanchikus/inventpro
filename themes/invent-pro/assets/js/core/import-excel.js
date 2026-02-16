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
            const row = checkbox.closest(".import__item.table-item");
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

    function closeApplyConfirmLayer() {
        const existingLayer = document.getElementById(
            "import-apply-confirm-layer",
        );
        if (existingLayer) {
            existingLayer.remove();
        }
    }

    function showApplyConfirmModal(selectedCount, updates) {
        pendingApplyUpdates = updates;
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

    function applyDifferences(updates) {
        $.request("onApplyDifferences", {
            data: { updates },
            beforeSend() {
                btnProgress.start();
            },
            success(data) {
                btnProgress.update(100);
                handleServerResponse(data);
                Modal.hide();
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
        const rows = Array.from(
            document.querySelectorAll(".import__item.table-item"),
        );

        return rows
            .map((row) => {
                const checkbox = row.querySelector(".diff-checkbox");
                if (!checkbox) return null;

                return {
                    id: checkbox.dataset.id || "",
                    inv_number: checkbox.value || "",
                    name:
                        row
                            .querySelector(".import__name")
                            ?.textContent?.trim() || "",
                    current_quantity:
                        row
                            .querySelector(".import__quantity")
                            ?.textContent?.trim() || "",
                    excel_quantity:
                        row
                            .querySelector(".import__quantity-excel")
                            ?.textContent?.trim() || "",
                    current_price:
                        row
                            .querySelector(".import__price")
                            ?.textContent?.trim() || "",
                    excel_price:
                        row
                            .querySelector(".import__price-excel")
                            ?.textContent?.trim() || "",
                    current_sum:
                        row
                            .querySelector(".import__sum")
                            ?.textContent?.trim() || "",
                    excel_sum:
                        row
                            .querySelector(".import__sum-excel")
                            ?.textContent?.trim() || "",
                };
            })
            .filter(Boolean);
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
    });

    document.addEventListener("click", function (event) {
        if (event.target && event.target.id === "cancel-apply-confirm") {
            closeApplyConfirmLayer();
            return;
        }

        if (event.target && event.target.id === "confirm-apply-confirm") {
            const updates = pendingApplyUpdates;
            pendingApplyUpdates = null;
            closeApplyConfirmLayer();

            if (!updates || Object.keys(updates).length === 0) {
                toast("Немає вибраних змін", "error");
                return;
            }

            applyDifferences(updates);
            return;
        }

        if (event.target && event.target.id === "cancel-differences") {
            Modal.hide();
            return;
        }

        if (event.target && event.target.id === "download-differences") {
            const rows = collectDifferencesForDownload();

            if (!rows.length) {
                toast("Немає відмінностей для експорту", "info");
                return;
            }

            $.request("onDownloadDifferencesExcel", {
                data: { rows },
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
            const updates = collectSelectedUpdates();

            if (Object.keys(updates).length === 0) {
                toast("Выберите хотя бы один продукт", "error");
                return;
            }

            showApplyConfirmModal(Object.keys(updates).length, updates);
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
        });

        observer.observe(modalContent, {
            childList: true,
            subtree: true,
        });
    }
});
