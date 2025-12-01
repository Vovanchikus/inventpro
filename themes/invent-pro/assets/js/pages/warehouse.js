document.addEventListener("DOMContentLoaded", () => {
    localStorage.removeItem("selectedProducts");

    function getSelected() {
        return JSON.parse(localStorage.getItem("selectedProducts") || "[]");
    }

    function saveSelected(list) {
        localStorage.setItem("selectedProducts", JSON.stringify(list));
    }

    function updateBottomBar() {
        const selected = getSelected();
        const bar = document.getElementById("bottomBar");

        if (selected.length > 0) {
            bar.classList.remove("hidden");
            document.getElementById(
                "bottomBarCount"
            ).textContent = `${selected.length}`;
        } else {
            bar.classList.add("hidden");
        }
    }

    document.querySelectorAll(".product-check").forEach((cb) => {
        cb.addEventListener("change", () => {
            let selected = getSelected();
            const id = parseInt(cb.dataset.id);

            if (cb.checked) {
                if (!selected.includes(id)) selected.push(id);
            } else {
                selected = selected.filter((x) => x !== id);
            }

            saveSelected(selected);
            updateBottomBar();
        });
    });

    // обновляем панель при загрузке
    updateBottomBar();
});
