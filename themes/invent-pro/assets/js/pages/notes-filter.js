document.addEventListener("DOMContentLoaded", () => {
    const filterWrap = document.querySelector("[data-notes-filter]");
    const list = document.querySelector("[data-notes-list]");

    if (!filterWrap || !list) return;

    let currentFilter =
        filterWrap.querySelector(".notes-filter__tab.is-active")?.dataset
            .filter || "all";

    const getNotes = () => Array.from(list.querySelectorAll(".note-card"));

    const getCreatedAt = (el) => {
        const raw = el.dataset.createdAt;
        const num = Number(raw);
        return Number.isFinite(num) ? num : 0;
    };

    const sortNotesByCreatedDesc = (notes) =>
        notes.sort((a, b) => getCreatedAt(b) - getCreatedAt(a));

    const hideNote = (note) => {
        note.classList.add("is-hidden");
        note.classList.remove("is-showing");
    };

    const showNote = (note) => {
        const wasHidden = note.classList.contains("is-hidden");

        note.classList.remove("is-hidden");

        if (wasHidden) {
            note.classList.add("is-appearing");
            note.getBoundingClientRect();
            requestAnimationFrame(() => {
                note.classList.remove("is-appearing");
            });
        }
    };

    const setHiddenByFilter = (notes, filterKey) => {
        const shouldShow = (note) => {
            if (filterKey === "all") return true;
            return (note.dataset.statusKey || "") === filterKey;
        };

        notes.forEach((note) => {
            if (shouldShow(note)) {
                showNote(note);
            } else {
                hideNote(note);
            }
        });
    };

    const updateCounts = (notes) => {
        const totals = {
            all: notes.length,
            in_development: 0,
            document_prepared: 0,
            in_accounting: 0,
            completed: 0,
        };

        notes.forEach((note) => {
            const statusKey = note.dataset.statusKey || "";
            if (totals[statusKey] !== undefined) {
                totals[statusKey] += 1;
            }
        });

        filterWrap.querySelectorAll(".notes-filter__tab").forEach((tab) => {
            const key = tab.dataset.filter || "all";
            const countEl = tab.querySelector("[data-count]");
            if (!countEl) return;
            countEl.textContent = String(totals[key] ?? 0);
        });
    };

    const animateReorder = (notes, firstRects) => {
        const sorted = sortNotesByCreatedDesc(notes);
        const fragment = document.createDocumentFragment();
        sorted.forEach((note) => fragment.appendChild(note));
        list.appendChild(fragment);

        sorted.forEach((note) => {
            const first = firstRects.get(note);
            if (!first) return;
            const last = note.getBoundingClientRect();
            const dx = first.left - last.left;
            const dy = first.top - last.top;

            if (dx || dy) {
                note.style.transform = `translate(${dx}px, ${dy}px)`;
                note.style.transition = "transform 0s";
                note.getBoundingClientRect();
                requestAnimationFrame(() => {
                    note.style.transition = "";
                    note.style.transform = "";
                });
            }
        });
    };

    let isApplying = false;

    const applyFilter = (filterKey) => {
        if (isApplying) return;
        isApplying = true;

        observer.disconnect();

        currentFilter = filterKey || "all";

        const notes = getNotes();
        updateCounts(notes);

        const firstRects = new Map();
        notes.forEach((note) =>
            firstRects.set(note, note.getBoundingClientRect()),
        );

        setHiddenByFilter(notes, currentFilter);
        const visible = notes.filter(
            (note) => !note.classList.contains("is-hidden"),
        );
        animateReorder(visible, firstRects);

        observer.observe(list, { childList: true, subtree: false });
        isApplying = false;
    };

    filterWrap.addEventListener("click", (event) => {
        const tab = event.target.closest(".notes-filter__tab");
        if (!tab) return;

        filterWrap
            .querySelectorAll(".notes-filter__tab")
            .forEach((btn) => btn.classList.remove("is-active"));
        tab.classList.add("is-active");

        applyFilter(tab.dataset.filter || "all");
    });

    const observer = new MutationObserver(() => {
        if (isApplying) return;
        applyFilter(currentFilter);
    });

    observer.observe(list, { childList: true, subtree: false });

    applyFilter(currentFilter);
});
