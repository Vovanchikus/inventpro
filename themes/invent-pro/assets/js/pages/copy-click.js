function copyText(text) {
    if (!text) return;

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard
            .writeText(text)
            .then(() => {
                toast("Скопировано: " + text, "success", 4000, "top-center");
            })
            .catch((err) => {
                console.error(err);
                toast("Ошибка копирования", "error", 4000, "top-center");
            });
    } else {
        const tempInput = document.createElement("input");
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        try {
            document.execCommand("copy");
            toast(
                "Скопировано: <b>" + text + "</b>",
                "success",
                4000,
                "top-center"
            );
        } catch (err) {
            console.error(err);
            toast("Ошибка копирования", "error", 4000, "top-center");
        }
        document.body.removeChild(tempInput);
    }
}
