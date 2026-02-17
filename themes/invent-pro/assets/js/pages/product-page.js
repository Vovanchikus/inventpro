document.addEventListener("DOMContentLoaded", () => {
    const productPage = document.querySelector(".product-page");
    const placeholderImagePath =
        productPage?.dataset.placeholderImage ||
        "/themes/invent-pro/assets/img/icon/no-pictures.svg";

    function escapeHtml(text) {
        return String(text || "")
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }

    function normalizeImages(images) {
        if (!Array.isArray(images)) return [];

        return images
            .map((image) => ({
                id: Number(image.id),
                path: image.path || image.url || "",
                width: Number(image.width) || 0,
                height: Number(image.height) || 0,
            }))
            .filter((image) => image.id && image.path);
    }

    function getImagesFromSlider() {
        return normalizeImages(
            Array.from(
                document.querySelectorAll(
                    ".ip-slider__main .ip-slider__lightbox",
                ),
            ).map((link) => {
                const size = (link.getAttribute("data-lg-size") || "0-0").split(
                    "-",
                );
                return {
                    id: Number(link.getAttribute("data-image-id")),
                    path: link.getAttribute("href"),
                    width: Number(size[0]) || 0,
                    height: Number(size[1]) || 0,
                };
            }),
        );
    }

    function renderSlider(images) {
        const mainWrapper = document.querySelector(
            ".ip-slider__main .swiper-wrapper",
        );
        const thumbsWrapper = document.querySelector(
            ".ip-slider__thumbs .swiper-wrapper",
        );

        if (!mainWrapper || !thumbsWrapper) return;

        if (!images.length) {
            const placeholderImg = escapeHtml(placeholderImagePath);
            mainWrapper.innerHTML = `
                <div class="swiper-slide">
                    <img src="${placeholderImg}" alt="No image available">
                </div>
            `;

            thumbsWrapper.innerHTML = `
                <div class="swiper-slide">
                    <img src="${placeholderImg}" alt="No image available">
                </div>
            `;

            if (typeof window.initProductPageSlider === "function") {
                window.initProductPageSlider();
            }

            return;
        }

        mainWrapper.innerHTML = images
            .map(
                (image) => `
                    <div class="swiper-slide" data-image-id="${image.id}">
                        <a href="${escapeHtml(image.path)}" class="ip-slider__lightbox" data-image-id="${image.id}" data-lg-size="${image.width}-${image.height}">
                            <img src="${escapeHtml(image.path)}" alt="product image">
                        </a>
                    </div>
                `,
            )
            .join("");

        thumbsWrapper.innerHTML = images
            .map(
                (image, index) => `
                    <div class="swiper-slide" data-image-id="${image.id}">
                        <img src="${escapeHtml(image.path)}" alt="product thumb ${index + 1}">
                    </div>
                `,
            )
            .join("");

        if (typeof window.initProductPageSlider === "function") {
            window.initProductPageSlider();
        }
    }

    const productId = Number(productPage?.dataset.productId);
    const form = productId
        ? document.getElementById(`uploadImageForm_${productId}`)
        : null;
    const hiddenUploadInput = productId
        ? document.getElementById(`uploadImageInput_${productId}`)
        : null;
    const uploadBtn = productId
        ? document.getElementById(`btnUploadImage_${productId}`)
        : null;

    if (form && hiddenUploadInput && uploadBtn) {
        let imagesState = getImagesFromSlider();
        const ORDER_ANIMATION_MS = 220;
        const REMOVE_ANIMATION_MS = 190;

        const request = (handler, options) =>
            new Promise((resolve, reject) => {
                $(form).request(handler, {
                    ...options,
                    success: (response) => resolve(response || {}),
                    error: (response) => reject(response),
                });
            });

        function modalListHtml(images) {
            if (!images.length) {
                return '<div class="product-image-manager__empty">Зображень поки немає</div>';
            }

            return images
                .map(
                    (image) => `
                        <div class="product-image-manager__item" draggable="true" data-image-id="${image.id}" style="width:72px;height:72px;">
                            <img src="${escapeHtml(image.path)}" alt="image" width="72" height="72" style="width:100%;height:100%;object-fit:cover;display:block;">
                            <button type="button" class="product-image-manager__remove" data-remove-id="${image.id}" aria-label="Видалити фото">×</button>
                        </div>
                    `,
                )
                .join("");
        }

        function buildModalHtml(images) {
            return `
                <div class="product-image-manager">
                    <div class="product-image-manager__hint">Перетягніть фото, щоб змінити порядок на сторінці товару</div>
                    <div class="product-image-manager__list" id="productImageList">${modalListHtml(images)}</div>
                    <div class="product-image-manager__dropzone" id="productImageDropzone">
                        <input id="productImageModalInput" type="file" accept="image/*" multiple>
                        <div class="product-image-manager__dropzone-title">Перетягніть нові фото сюди</div>
                        <div class="product-image-manager__dropzone-subtitle">або натисніть для вибору файлів</div>
                    </div>
                </div>
            `;
        }

        function updateStateFromResponse(response) {
            if (!response || !Array.isArray(response.images)) return;

            const nextImages = normalizeImages(response.images);
            imagesState = nextImages;
            renderSlider(imagesState);
        }

        async function saveOrder() {
            const response = await request("onReorderProductImages", {
                data: {
                    product_id: productId,
                    order: imagesState.map((image) => image.id),
                },
            });

            if (response && response.error) {
                throw new Error(response.error);
            }

            updateStateFromResponse(response);
            return response;
        }

        async function uploadFiles(fileList) {
            const files = Array.from(fileList || []);
            if (!files.length) return;

            const dt = new DataTransfer();
            files.forEach((file) => dt.items.add(file));
            hiddenUploadInput.files = dt.files;

            const response = await request("onUploadProductImage", {
                files: true,
                data: { product_id: productId },
            });

            hiddenUploadInput.value = "";
            updateStateFromResponse(response);
            return response;
        }

        async function removeImage(imageId) {
            const response = await request("onDeleteProductImage", {
                data: {
                    product_id: productId,
                    image_id: imageId,
                },
            });
            updateStateFromResponse(response);
            return response;
        }

        function rerenderModalList() {
            const list = document.getElementById("productImageList");
            if (!list) return;
            list.innerHTML = modalListHtml(imagesState);
            bindModalActions();
        }

        function wait(ms) {
            return new Promise((resolve) => setTimeout(resolve, ms));
        }

        function captureItemRects(list) {
            const map = new Map();
            if (!list) return map;

            list.querySelectorAll(".product-image-manager__item").forEach(
                (item) => {
                    const id = Number(item.dataset.imageId);
                    if (!id) return;
                    map.set(id, item.getBoundingClientRect());
                },
            );

            return map;
        }

        function animateListFlip(list, previousRects) {
            if (!list || !previousRects || !previousRects.size) return;

            requestAnimationFrame(() => {
                list.querySelectorAll(".product-image-manager__item").forEach(
                    (item) => {
                        const id = Number(item.dataset.imageId);
                        if (!id || !previousRects.has(id)) return;

                        const oldRect = previousRects.get(id);
                        const newRect = item.getBoundingClientRect();
                        const deltaX = oldRect.left - newRect.left;
                        const deltaY = oldRect.top - newRect.top;

                        if (!deltaX && !deltaY) return;

                        item.style.transition = "transform 0ms";
                        item.style.transform = `translate(${deltaX}px, ${deltaY}px)`;

                        requestAnimationFrame(() => {
                            item.style.transition = `transform ${ORDER_ANIMATION_MS}ms cubic-bezier(0.2, 0.8, 0.2, 1)`;
                            item.style.transform = "";

                            const clearInline = () => {
                                item.style.transition = "";
                                item.removeEventListener(
                                    "transitionend",
                                    clearInline,
                                );
                            };

                            item.addEventListener("transitionend", clearInline);
                        });
                    },
                );
            });
        }

        function rerenderModalListAnimated(previousRects) {
            const list = document.getElementById("productImageList");
            if (!list) return;

            list.innerHTML = modalListHtml(imagesState);
            bindModalActions();
            animateListFlip(list, previousRects);
        }

        function bindModalActions() {
            const list = document.getElementById("productImageList");
            const dropzone = document.getElementById("productImageDropzone");
            const modalInput = document.getElementById(
                "productImageModalInput",
            );

            if (!list || !dropzone || !modalInput) return;

            let draggedId = null;

            list.querySelectorAll(".product-image-manager__item").forEach(
                (item) => {
                    item.addEventListener("dragstart", (event) => {
                        draggedId = Number(item.dataset.imageId);
                        item.classList.add("is-dragging");

                        if (event.dataTransfer) {
                            event.dataTransfer.effectAllowed = "move";
                            event.dataTransfer.setData(
                                "text/plain",
                                String(draggedId),
                            );

                            const ghost = item.cloneNode(true);
                            ghost.classList.add("drag-ghost");
                            ghost.style.position = "fixed";
                            ghost.style.top = "-9999px";
                            ghost.style.left = "-9999px";
                            ghost.style.width = `${item.offsetWidth}px`;
                            ghost.style.height = `${item.offsetHeight}px`;
                            document.body.appendChild(ghost);
                            event.dataTransfer.setDragImage(
                                ghost,
                                item.offsetWidth / 2,
                                item.offsetHeight / 2,
                            );
                            requestAnimationFrame(() => ghost.remove());
                        }
                    });

                    item.addEventListener("dragend", () => {
                        draggedId = null;
                        item.classList.remove("is-dragging");
                        list.querySelectorAll(".is-drop-target").forEach(
                            (el) => {
                                el.classList.remove("is-drop-target");
                            },
                        );
                    });

                    item.addEventListener("dragover", (event) => {
                        event.preventDefault();
                    });

                    item.addEventListener("dragenter", () => {
                        if (!draggedId) return;
                        if (Number(item.dataset.imageId) === draggedId) return;
                        item.classList.add("is-drop-target");
                    });

                    item.addEventListener("dragleave", () => {
                        item.classList.remove("is-drop-target");
                    });

                    item.addEventListener("drop", async (event) => {
                        event.preventDefault();
                        if (!draggedId) return;

                        const targetId = Number(item.dataset.imageId);
                        if (!targetId || draggedId === targetId) return;

                        const fromIndex = imagesState.findIndex(
                            (img) => img.id === draggedId,
                        );
                        const toIndex = imagesState.findIndex(
                            (img) => img.id === targetId,
                        );

                        if (
                            fromIndex < 0 ||
                            toIndex < 0 ||
                            fromIndex === toIndex
                        )
                            return;

                        const moved = imagesState.splice(fromIndex, 1)[0];
                        const rect = item.getBoundingClientRect();
                        const insertAfter =
                            event.clientX > rect.left + rect.width / 2;
                        const previousRects = captureItemRects(list);

                        imagesState.splice(
                            insertAfter ? toIndex + 1 : toIndex,
                            0,
                            moved,
                        );

                        renderSlider(imagesState);
                        rerenderModalListAnimated(previousRects);
                        try {
                            await saveOrder();
                        } catch {
                            alert("Не вдалося зберегти порядок фото");
                        }
                    });
                },
            );

            list.onclick = async (event) => {
                const removeBtn = event.target.closest(
                    ".product-image-manager__remove",
                );
                if (!removeBtn) return;

                const imageId = Number(removeBtn.dataset.removeId);
                if (!imageId) return;

                const item = removeBtn.closest(".product-image-manager__item");
                const previousRects = captureItemRects(list);

                try {
                    if (item) {
                        item.classList.add("is-removing");
                        await wait(REMOVE_ANIMATION_MS);
                    }

                    await removeImage(imageId);
                    rerenderModalListAnimated(previousRects);
                } catch {
                    if (item) {
                        item.classList.remove("is-removing");
                    }
                    alert("Не вдалося видалити фото");
                }
            };

            modalInput.onchange = async () => {
                const previousRects = captureItemRects(list);

                try {
                    await uploadFiles(modalInput.files);
                    modalInput.value = "";
                    rerenderModalListAnimated(previousRects);
                } catch {
                    alert("Помилка завантаження фото");
                }
            };

            dropzone.onclick = () => modalInput.click();
            dropzone.ondragover = (event) => {
                event.preventDefault();
                dropzone.classList.add("is-over");
            };
            dropzone.ondragleave = () => {
                dropzone.classList.remove("is-over");
            };
            dropzone.ondrop = async (event) => {
                event.preventDefault();
                dropzone.classList.remove("is-over");

                const droppedFiles = event.dataTransfer?.files;
                if (!droppedFiles?.length) return;

                const previousRects = captureItemRects(list);

                try {
                    await uploadFiles(droppedFiles);
                    rerenderModalListAnimated(previousRects);
                } catch {
                    alert("Помилка завантаження фото");
                }
            };
        }

        uploadBtn.addEventListener("click", () => {
            imagesState = getImagesFromSlider();
            Modal.window.style.width = "780px";
            Modal.show(
                buildModalHtml(imagesState),
                "info",
                "Фото товару",
                "Налаштування відображення фото товару",
                `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 7.75C19 8.16421 19.3358 8.5 19.75 8.5C20.1642 8.5 20.5 8.16421 20.5 7.75V6H22.25C22.6642 6 23 5.66421 23 5.25C23 4.83579 22.6642 4.5 22.25 4.5H20.5V2.75C20.5 2.33579 20.1642 2 19.75 2C19.3358 2 19 2.33579 19 2.75V4.5H17.25C16.8358 4.5 16.5 4.83579 16.5 5.25C16.5 5.66421 16.8358 6 17.25 6H19V7.75Z" fill="currentColor"/>
                    <path d="M14.0268 2.00012L10.4331 2.00006C10.0501 1.99962 9.71283 1.99923 9.39446 2.09443C9.11521 2.17793 8.85485 2.31489 8.62784 2.49769C8.36903 2.70611 8.17827 2.98426 7.96165 3.30012L7.13685 4.49994C6.26819 4.50012 5.56508 4.50313 4.99013 4.5501C4.36012 4.60157 3.81824 4.70956 3.32054 4.96315C2.52085 5.37061 1.87068 6.02078 1.46322 6.82047C1.20963 7.31816 1.10165 7.86004 1.05018 8.49006C0.999989 9.10432 0.999994 9.86488 1 10.8173V15.6826C0.999994 16.635 0.999989 17.3955 1.05018 18.0098C1.10165 18.6398 1.20963 19.1817 1.46322 19.6794C1.87068 20.4791 2.52085 21.1292 3.32054 21.5367C3.81824 21.7903 4.36012 21.8983 4.99013 21.9498C5.60438 21.9999 6.36496 21.9999 7.31737 21.9999H19.2502C21.5973 21.9999 23.5 20.0972 23.5 17.7501L23.5 17.7451L23.5 17.7401V10.8499C23.5 10.5993 23.5 10.363 23.4992 10.141C23.4977 9.72683 23.1607 9.39227 22.7465 9.39378C22.3323 9.39528 21.9977 9.73229 21.9992 10.1465C22 10.3653 22 10.5987 22 10.8499V17.7401L22 17.7451L22 17.7501C22 19.2688 20.7689 20.4999 19.2502 20.4999H7.35C6.35753 20.4999 5.65829 20.4993 5.11228 20.4547C4.57503 20.4108 4.25252 20.3281 4.00153 20.2002C3.48408 19.9365 3.06338 19.5158 2.79973 18.9984C2.67184 18.7474 2.58909 18.4249 2.54519 17.8876C2.50058 17.3416 2.5 16.6424 2.5 15.6499V10.8499C2.5 9.85746 2.50058 9.15821 2.54519 8.61221C2.58909 8.07495 2.67184 7.75245 2.79973 7.50145C3.06338 6.98401 3.48408 6.56331 4.00153 6.29966C4.25252 6.17177 4.57503 6.08901 5.11228 6.04512C5.65829 6.00051 6.35753 5.99992 7.35 5.99992H7.53138C7.77836 5.99992 8.0095 5.87834 8.14942 5.67482L9.1528 4.21535C9.44048 3.79691 9.50333 3.71857 9.56864 3.66598C9.64431 3.60504 9.7311 3.55939 9.82418 3.53156C9.90453 3.50753 10.0047 3.50012 10.5125 3.50012L14.0267 3.50012C14.4409 3.50017 14.7767 3.16442 14.7768 2.75021C14.7768 2.33599 14.4411 2.00017 14.0268 2.00012Z" fill="currentColor"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.9999 13.2657L16.9999 13.2641L17 13.25C17 10.6266 14.8734 8.5 12.25 8.5C9.62665 8.5 7.5 10.6266 7.5 13.25C7.5 15.8734 9.62665 18 12.25 18C14.8655 18 16.98 15.8728 16.9999 13.2657ZM15.4999 13.2543L15.5 13.2467C15.4982 11.4533 14.0438 10 12.25 10C10.4551 10 9 11.4551 9 13.25C9 15.0449 10.4551 16.5 12.25 16.5C14.0319 16.5 15.4862 15.0472 15.4999 13.2543Z" fill="currentColor"/>
                 </svg>`,
            );
            bindModalActions();
        });
    }

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
