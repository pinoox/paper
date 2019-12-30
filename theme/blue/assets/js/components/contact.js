$(document).ready(function () {
    $('.contact .form').on('submit', function (e) {
        e.preventDefault();
        let formId = "#" + $(this).attr('id');
        let formData = getData(formId);
        $.ajax({
            url: PINOOX.URL.APP + "sendContact/",
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            contentType: false,
            processData: false,
            success: function (json) {
                if (json.status) {
                    $(formId + " #message").html(alertMessage(json.message, 'success'));
                    $(formId).find("input[type=text]").val('');
                    $(formId).find("textarea").val('');

                } else {
                    $(formId + " #message").html(alertMessage(json.message, 'danger'));
                }
            }
        });
    });

});

function alertMessage(text, status) {
    return '<div class="alert alert-' + status + ' animated faster fadeIn">\n' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
        '<span aria-hidden="true">&times;</span></button>' +
        '<div class="text">' + text + '</div></div>';
}

function getData(Id) {
    let formData = new FormData();
    $.each($(Id).serializeArray(), function (i, field) {
        formData.append(field.name, field.value);
    });
    return formData;
}
