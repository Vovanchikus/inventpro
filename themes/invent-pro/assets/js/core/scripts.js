// Кнопка СОЗДАТЬ в шапке
document.addEventListener("DOMContentLoaded", () => {
    var btn = document.getElementById("headerCreateButton");
    var dropdown = document.querySelector(".header__create-dropdown");

    if (!btn || !dropdown) return;

    // Toggle dropdown visibility by toggling the 'show' class
    btn.addEventListener("click", function (e) {
        e.preventDefault();
        dropdown.classList.toggle("show");
    });

    // Close when clicking outside the button/dropdown
    document.addEventListener("click", function (e) {
        if (!dropdown.classList.contains("show")) return;
        var target = e.target;
        if (target === btn || btn.contains(target) || dropdown.contains(target))
            return;
        dropdown.classList.remove("show");
    });

    // Close on Escape key
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" || e.key === "Esc") {
            dropdown.classList.remove("show");
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    if (typeof OverlayScrollbars !== "function") return;

    const optionLists = document.querySelectorAll(".options");
    if (optionLists.length > 0) {
        OverlayScrollbars(optionLists, {
            scrollbars: {
                visibility: "auto",
                autoHide: "leave",
                autoHideDelay: 800,
                dragScrolling: true,
            },
        });
    }

    const otherDocs = document.querySelector(".product-page__other-doc-list");
    if (!otherDocs) return;

    OverlayScrollbars(otherDocs, {
        scrollbars: {
            visibility: "auto",
            autoHide: "leave",
            autoHideDelay: 800,
            dragScrolling: true,
        },
    });

    document.addEventListener("click", (event) => {
        const selected = event.target.closest(".custom-select .selected");
        if (!selected) return;
        const select = selected.closest(".custom-select");
        const options = select?.querySelector(".options");
        if (!options) return;

        setTimeout(() => {
            const instance = OverlayScrollbars(options);
            if (instance) instance.update();
        }, 0);
    });
});
