document.addEventListener("DOMContentLoaded", () => {
    // Клик по кнопке загрузки
    document.body.addEventListener("click", function (e) {
        const btn = e.target.closest(
            ".btn-upload-image, [id^='btnUploadImage_']"
        );
        if (!btn) return;

        const productId = btn.dataset.productId;
        const fileInput = document.querySelector(
            `#uploadImageInput_${productId}`
        );
        if (!fileInput) return;

        fileInput.click();
    });

    // Выбор файлов
    document
        .querySelectorAll('input[type="file"][id^="uploadImageInput_"]')
        .forEach((input) => {
            input.addEventListener("change", function () {
                const form = this.closest("form");
                if (!form) return;

                const requestHandler = form.getAttribute("data-request");

                $(form).request(requestHandler, {
                    files: true,
                    success: function (response) {
                        if (
                            response.image_paths &&
                            response.image_paths.length
                        ) {
                            const imgContainer = document.querySelector(
                                ".product-page__images"
                            );
                            response.image_paths.forEach((path) => {
                                const img = document.createElement("img");
                                img.src = path;
                                imgContainer.appendChild(img);
                            });
                        }
                    },
                    error: function () {
                        alert("Ошибка загрузки изображения");
                    },
                });
            });
        });

    // Модалка QR

    Modal.init();

    const btnQR = document.getElementById("btnQR");
    if (btnQR) {
        btnQR.addEventListener("click", () => {
            const qrCode = btnQR.dataset.qrcode;

            const content = qrCode
                ? `<img src="${qrCode}" alt="QR код товара">`
                : `<p>Товар не найден.</p>`;

            Modal.window.style.width = "min-content";
            Modal.show(content, "info", "QR код товара");
        });
    }
});
