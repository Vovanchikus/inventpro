document.addEventListener("DOMContentLoaded", () => {
    // Получаем GET-параметры сразу
    const urlParams = new URLSearchParams(window.location.search);
    const typeValue = urlParams.get("type") || "";
    const counteragentValue = urlParams.get("counteragent") || "";

    // Устанавливаем их в скрытые поля
    document.getElementById("typeInput").value = typeValue;
    document.getElementById("counteragentInput").value = counteragentValue;

    const selects = document.querySelectorAll(".custom-select");

    selects.forEach((sel) => {
        const selected = sel.querySelector(".selected");
        const options = sel.querySelector(".options");
        const paramName = sel.dataset.name;

        // Выбираем правильное значение для dropdown
        let value = "";
        if (paramName === "type") {
            value = typeValue;
        } else if (paramName === "counteragent") {
            value = counteragentValue;
        }

        if (value) {
            const opt = options.querySelector(`.option[data-value="${value}"]`);
            if (opt) {
                selected.innerHTML = opt.innerHTML;
                sel.dataset.value = value;
            }
        }

        // Открытие/закрытие dropdown
        selected.addEventListener("click", () => {
            sel.classList.toggle("active");
        });

        // Выбор значения
        options.querySelectorAll(".option").forEach((option) => {
            option.addEventListener("click", () => {
                selected.innerHTML = option.innerHTML;
                sel.dataset.value = option.dataset.value;
                sel.classList.remove("active");

                if (sel.dataset.name === "type") {
                    document.getElementById("typeInput").value =
                        option.dataset.value;
                }
                if (sel.dataset.name === "counteragent") {
                    document.getElementById("counteragentInput").value =
                        option.dataset.value;
                }
            });
        });
    });

    // Закрытие dropdown при клике вне
    document.addEventListener("click", (e) => {
        selects.forEach((sel) => {
            if (!sel.contains(e.target)) sel.classList.remove("active");
        });
    });
});
