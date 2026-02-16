/**
 * toast
 * ----------
 * Универсальные уведомления (toasts)
 * Функции:
 * - toast(message, type, timeout, position) — показать уведомление
 */

window.toast = function (
    message,
    type = "info",
    timeout = 4000,
    position = "bottom-right"
) {
    let containerId = "toast-container-" + position;
    let container = document.getElementById(containerId);

    if (!container) {
        container = document.createElement("div");
        container.id = containerId;
        container.style.position = "fixed";
        container.style.zIndex = 9999;
        container.style.display = "flex";
        container.style.flexDirection = "column";
        container.style.gap = "10px";
        container.style.maxWidth = "400px";
        container.style.width = "auto";

        if (position === "bottom-right") {
            container.style.bottom = "20px";
            container.style.right = "20px";
            container.style.alignItems = "flex-end";
        } else if (position === "top-center") {
            container.style.top = "20px";
            container.style.left = "50%";
            container.style.transform = "translateX(-50%)";
            container.style.alignItems = "center";
        }

        document.body.appendChild(container);
    }

    const toastEl = document.createElement("div");
    toastEl.className = `toast ${type} ${position}`;
    toastEl.innerHTML = message;

    container.appendChild(toastEl);

    setTimeout(() => toastEl.classList.add("show"), 50);
    setTimeout(() => hideToast(toastEl), timeout);

    toastEl.addEventListener("click", () => hideToast(toastEl));
};

function hideToast(toastEl) {
    toastEl.classList.remove("show");
    toastEl.classList.add("hide");
    setTimeout(() => toastEl.remove(), 300);
}
