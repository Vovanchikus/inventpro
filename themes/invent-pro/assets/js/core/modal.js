/**
 * Modal
 * ----------
 * Универсальный компонент для модалок на сайте
 * Методы:
 * - Modal.show(html, type, title) — показать модалку
 * - Modal.hide() — скрыть модалку
 * - Modal.init() — навесить события закрытия
 */

class Modal {
    static container = document.getElementById("modal-container");
    static content = document.querySelector("#modal-container .modal-content");
    static window = document.querySelector("#modal-container .modal-window");
    static title = document.querySelector("#modal-container .modal-title");
    static closeBtn = document.querySelector("#modal-container .modal-close");

    static show(html, type = "info", title = "") {
        this.title.textContent = title;
        this.content.innerHTML = html;
        this.container.classList.add("active");
        this.content.style.color =
            type === "error" ? "red" : type === "success" ? "green" : "#000";
    }

    static hide() {
        this.container.classList.remove("active");
    }

    static init() {
        this.closeBtn.addEventListener("click", () => this.hide());
        this.container.addEventListener("click", (e) => {
            if (e.target === this.container) this.hide();
        });
    }
}

document.addEventListener("DOMContentLoaded", () => Modal.init());
