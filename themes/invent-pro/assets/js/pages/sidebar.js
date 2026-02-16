document.addEventListener("DOMContentLoaded", () => {
    const btnCategory = document.querySelector(".sidebar-category__button");
    const contentCategory = document.querySelector(
        ".sidebar-category__content"
    );

    if (!btnCategory || !contentCategory) return;

    // Клик по кнопке — переключаем класс .active
    btnCategory.addEventListener("click", (e) => {
        e.stopPropagation(); // чтобы клик не всплывал на document
        contentCategory.classList.toggle("active");
        btnCategory.classList.toggle("active");
    });

    // Клик внутри контента — чтобы не закрывалось
    contentCategory.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    // Клик вне кнопки и контента — закрываем
    document.addEventListener("click", () => {
        contentCategory.classList.remove("active");
        btnCategory.classList.remove("active");
    });
});
