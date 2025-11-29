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

    // -----------------------------
    // Обработка кликов на странице (для модалки различий)
    // -----------------------------
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
                beforeSend() {
                    btnProgress.start();
                },
                success(data) {
                    btnProgress.update(100);
                    handleServerResponse(data);
                },
                error(err) {
                    console.error("Ошибка AJAX при применении различий:", err);
                    alert("Произошла ошибка при отправке данных");
                    btnProgress.finish();
                },
                complete() {
                    btnProgress.finish();
                },
            });
        }
    });
});
