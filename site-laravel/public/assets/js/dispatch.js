const DISPATCH = true;

function newMarkFormOnChangeType($select) {
    let markType = $select.prop('value');
    $('.new-mark-form-input').prop('form', 'new-mark-form--' + markType);
    $('.new-mark-form').hide();
    $('#new-mark-form--' + markType).show();
}

function deleteObject(id, type) {
    fetch(`/api/${type}/${id}`, {
        method: "DELETE",
        credentials: "same-origin",
        headers: {
            "X-CSRF-TOKEN": Cookies.get("XSRF-TOKEN")
        }
    }).then(
        () => window.location.reload(),
        () => alert("Не удалось удалить объект."),
    );
}

function deleteMark(id) {
    deleteObject(id, "marks");
}

function blockSender(id) {
    fetch(`/api/banned-users`, {
        method: "POST",
        body: JSON.stringify({ id }),
        credentials: "same-origin",
        headers: {
            "X-CSRF-TOKEN": Cookies.get("XSRF-TOKEN")
        }
    }).then(
        () => window.location.reload(),
        () => alert("Не удалось заблокировать отправителя."),
    );
}
