document.addEventListener("click", (event) => {
    const tab = event.target.closest(".note-tab");
    if (!tab) return;

    const card = tab.closest(".note-card, .note-single");
    if (!card) return;

    const tabKey = tab.dataset.tab || "info";
    const tabs = card.querySelectorAll(".note-tab");
    const contents = card.querySelectorAll("[data-tab-content]");

    tabs.forEach((item) => {
        item.classList.toggle("note-tab--active", item.dataset.tab === tabKey);
    });
    contents.forEach((content) => {
        content.classList.toggle(
            "is-active",
            content.dataset.tabContent === tabKey,
        );
    });
});
