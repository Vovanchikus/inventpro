class Modal {
    static container = document.getElementById("modal-container");
    static content = document.querySelector("#modal-container .modal-content");
    static title = document.querySelector("#modal-container .modal-title"); // заголовок
    static closeBtn = document.querySelector("#modal-container .modal-close");

    static show(html, type = "info", title = "") {
        this.title.textContent = title; // вставляем заголовок
        this.content.innerHTML = html; // вставляем контент
        this.container.classList.add("active");

        // Цвет текста контента (можно отдельно для заголовка)
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
