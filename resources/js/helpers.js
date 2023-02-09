window.submitForm = function (event, successFunc, errorFunc) {
    let form = event.target.closest("form");
    let formData = new FormData(form);

    if (!_.isObjectLike(formData)) {
        return;
    }

    axios
        .request({
            url: form.action,
            method: form.method,
            data: formData,
        })
        .then((response) => {
            if (response.error) {
                if (_.isFunction(errorFunc)) {
                    errorFunc(response.error);
                }
                return;
            }

            if (_.isFunction(successFunc)) {
                successFunc(response.data);
            }
        })
        .catch((response) => {
            if (response.response.data.message) {
                if (_.isFunction(errorFunc)) {
                    errorFunc(response.response.data.message);
                }
                return;
            }

            console.log("Ошибка, которой нет в обработчике");
        });
};
