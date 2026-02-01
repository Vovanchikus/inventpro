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

    const root = document.querySelector(".options");
    const content = root.querySelector(".scroll-content");
    const thumb = root.querySelector(".thumb");

    let scrollY = 0;

    function update() {
        const rootH = root.clientHeight;
        const contentH = content.scrollHeight;

        const maxScroll = contentH - rootH;
        scrollY = Math.max(0, Math.min(scrollY, maxScroll));

        content.style.transform = `translateY(${-scrollY}px)`;

        const thumbH = Math.max(30, (rootH * rootH) / contentH);
        const thumbY = (scrollY * (rootH - thumbH)) / maxScroll;

        thumb.style.height = thumbH + "px";
        thumb.style.transform = `translateY(${thumbY}px)`;
    }

    root.addEventListener(
        "wheel",
        (e) => {
            e.preventDefault();
            scrollY += e.deltaY;
            update();
        },
        { passive: false },
    );

    update();
});

document.addEventListener("DOMContentLoaded", function () {
    OverlayScrollbars(document.querySelectorAll(".options"), {
        scrollbars: {
            visibility: "auto",
            autoHide: "leave",
            autoHideDelay: 800,
            dragScroll: true,
        },
    });
});
