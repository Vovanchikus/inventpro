document.addEventListener("DOMContentLoaded", () => {
    if (window.location.pathname.startsWith("/warehouse/")) {
        // Страница warehouse/:slug — не выполняем скрипт
        return;
    }

    const header = document.querySelector(".table-title");

    if (!header) return;

    // Слушаем скролл
    window.addEventListener("scroll", () => {
        const rect = header.getBoundingClientRect();

        if (rect.top <= 0) {
            // Шапка достигла верха окна — добавляем класс
            header.classList.add("scrolled");
        } else {
            // Шапка вернулась вниз — убираем класс
            header.classList.remove("scrolled");
        }
    });
});
