/**
 * button-progress.js
 * -----------------
 * Универсальный класс для отображения прогресса прямо в кнопке.
 * Текст кнопки обновляется как "Загрузка 45%", прогресс можно обновлять через update()
 */

class ButtonProgress {
    /**
     * @param {HTMLButtonElement} button - кнопка для прогресса
     * @param {Object} options
     *      options.startText: текст кнопки до загрузки (по умолчанию текущий текст)
     *      options.loadingText: базовый текст загрузки (по умолчанию "Загрузка")
     *      options.disable: блокировать кнопку во время загрузки (default: true)
     */
    constructor(button, options = {}) {
        if (!button) throw new Error("ButtonProgress: кнопка не передана");
        this.button = button;
        this.startText = options.startText || button.textContent;
        this.loadingText = options.loadingText || "Загрузка";
        this.disable = options.disable !== undefined ? options.disable : true;
        this.progress = 0;
        this.running = false;
    }

    /**
     * Начало прогресса
     */
    start() {
        if (this.running) return;
        this.running = true;
        this.progress = 0;
        if (this.disable) this.button.disabled = true;
        this._updateText();
    }

    /**
     * Обновление прогресса (0-100)
     * @param {number} value
     */
    update(value) {
        if (!this.running) return;
        this.progress = Math.min(Math.max(value, 0), 100);
        this._updateText();
    }

    /**
     * Завершение загрузки
     * @param {string} finishText - текст кнопки после завершения (по умолчанию startText)
     */
    finish(finishText) {
        if (!this.running) return;
        this.running = false;
        this.progress = 100;
        this.button.textContent = finishText || this.startText;
        if (this.disable) this.button.disabled = false;
    }

    /**
     * Внутреннее обновление текста кнопки
     */
    _updateText() {
        this.button.textContent = `${this.loadingText} ${Math.round(
            this.progress
        )}%`;
    }

    /**
     * Автоматический запуск асинхронной функции с прогрессом
     * asyncFunc должен принимать this и вызывать update(progress)
     */
    async runAsync(asyncFunc) {
        this.start();
        try {
            await asyncFunc(this);
        } finally {
            this.finish();
        }
    }
}

// Экспортируем глобально
window.ButtonProgress = ButtonProgress;
