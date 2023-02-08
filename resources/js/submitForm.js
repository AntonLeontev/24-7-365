window.submitForm = async function (event) {
    let form = event.target.closest("form");
    let formData = new FormData(form);
    let result;

    if (!_.isObjectLike(formData)) {
        return;
    }

    await axios
        .request({
            url: form.action,
            method: form.method,
            data: formData,
        })
        .then((response) => {
            if (response.error) {
                handleServerError(response.error);
                return;
            }
            result = response.data;
        })
        .catch((response) => {
            if (response.response.data.message) {
                handleValidationError(response.response.data.message);
                return;
            }

            if (response.response.data.exception) {
                handleServerError(response.response.data.message);
                return;
            }

            console.log("Ошибка, которой нет в обработчике");
        });

    if (result) {
        return result;
    }
};

function handleValidationError(message) {
    window.dispatchEvent(
        new CustomEvent("toast-error", {
            detail: {
                message: message,
            },
        })
    );
}

function handleServerError(message) {
    window.dispatchEvent(
        new CustomEvent("toast-error", {
            detail: { message: message },
        })
    );
}
