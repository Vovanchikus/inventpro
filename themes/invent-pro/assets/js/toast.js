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

    const toast = document.createElement("div");
    toast.className = `toast ${type} ${position}`;
    toast.innerHTML = message;

    container.appendChild(toast);

    setTimeout(() => toast.classList.add("show"), 50);
    setTimeout(() => hideToast(toast), timeout);

    toast.addEventListener("click", () => hideToast(toast));
};

function hideToast(toast) {
    toast.classList.remove("show");
    toast.classList.add("hide");
    setTimeout(() => toast.remove(), 300);
}
