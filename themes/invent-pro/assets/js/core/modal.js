/**
 * Modal
 * ----------
 * Универсальный компонент для модалок на сайте
 * Методы:
 * - Modal.show(html, type, title, subtitle, iconSvg) — показать модалку
 * - Modal.hide() — скрыть модалку
 * - Modal.init() — навесить события закрытия
 */

class Modal {
    static warningIconSvg =
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8.25C12.4142 8.25 12.75 8.58579 12.75 9V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V9C11.25 8.58579 11.5858 8.25 12 8.25Z" fill="currentColor"/><path d="M12 16.75C12.5523 16.75 13 16.3023 13 15.75C13 15.1977 12.5523 14.75 12 14.75C11.4477 14.75 11 15.1977 11 15.75C11 16.3023 11.4477 16.75 12 16.75Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M10.3196 3.41968C11.0599 2.14559 12.9401 2.14558 13.6804 3.41968L21.4111 16.725C22.157 18.0088 21.2293 19.625 19.7307 19.625H4.26925C2.77067 19.625 1.84304 18.0088 2.58893 16.725L10.3196 3.41968ZM12.3831 4.17304C12.2597 3.96064 11.9403 3.96064 11.8169 4.17304L4.08619 17.4784C3.96187 17.6924 4.11647 17.9688 4.26925 17.9688H19.7307C19.8835 17.9688 20.0381 17.6924 19.9138 17.4784L12.3831 4.17304Z" fill="currentColor"/></svg>';

    static container = document.getElementById("modal-container");
    static content = document.querySelector("#modal-container .modal-content");
    static window = document.querySelector("#modal-container .modal-window");
    static title = document.querySelector("#modal-container .modal-title");
    static subtitle = document.querySelector(
        "#modal-container .modal-subtitle",
    );
    static icon = document.querySelector("#modal-container .modal-icon");
    static closeBtn = document.querySelector("#modal-container .modal-close");

    static show(html, type = "info", title = "", subtitle = "", iconSvg = "") {
        this.title.textContent = title;
        this.subtitle.textContent = subtitle;
        this.content.innerHTML = html;

        const resolvedIcon =
            iconSvg || (type === "warning" ? this.warningIconSvg : "");
        this.icon.innerHTML = resolvedIcon;

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
