document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector('[class*="-table"][class*="table"]');
    if (!table) return;

    const header = table.querySelector(".table-title");
    if (!header) return;

    // Создаём клон шапки
    const stickyHeader = header.cloneNode(true);
    stickyHeader.classList.add("sticky-header");
    stickyHeader.style.position = "fixed";
    stickyHeader.style.top = "0";
    stickyHeader.style.left = header.getBoundingClientRect().left + "px";
    stickyHeader.style.display = "none";
    // stickyHeader.style.background = "#fff";
    stickyHeader.style.zIndex = "1000";
    stickyHeader.style.pointerEvents = "none";
    stickyHeader.style.boxShadow = "0 2px 5px rgba(0,0,0,0.1)";
    stickyHeader.style.justifyContent = "flex-start"; // если используем flex
    document.body.appendChild(stickyHeader);

    function syncWidths() {
        const originalCols = header.children;
        const cloneCols = stickyHeader.children;

        for (let i = 0; i < originalCols.length; i++) {
            const width = originalCols[i].getBoundingClientRect().width + "px";
            cloneCols[i].style.width = width;
        }

        stickyHeader.style.width = header.getBoundingClientRect().width + "px";
        stickyHeader.style.left = header.getBoundingClientRect().left + "px";
    }

    function updateSticky() {
        const rect = header.getBoundingClientRect();
        const tableRect = table.getBoundingClientRect();

        if (rect.top < 0 && tableRect.bottom > 0) {
            stickyHeader.style.display = "grid";
            syncWidths();
        } else {
            stickyHeader.style.display = "none";
        }
    }

    window.addEventListener("scroll", updateSticky);
    window.addEventListener("resize", updateSticky);
});
