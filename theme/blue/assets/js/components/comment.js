$(document).ready(function () {
    $('.comments .form').on('submit', function (e) {
        e.preventDefault();
        let formId = "#" + $(this).attr('id');
        let article_id = $('.comments').find('#article_id').val();
        let formData = getData(formId);
        $.ajax({
            url: PINOOX.URL.APP + "sendComment/" + article_id,
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            contentType: false,
            processData: false,
            success: function (json) {
                if (json.status) {
                    $(formId + " #cm-message").html(alertMessage(json.message, 'success'));
                    $(formId).find("input[type=text]").val('');
                    $(formId).find("textarea").val('');

                } else {
                    $(formId + " #cm-message").html(alertMessage(json.message, 'danger'));
                }
            }
        });
    });

    $('.comments .actions [data-action="replyComment"]').on('click', function (e) {
        $(this).parentsUntil('.cm-box').parent().find('.cm-reply:first').toggle();
    });

    $('.comments [data-action="cancelReply"]').on('click', function (e) {
        $(this).parentsUntil('.cm-reply').parent().hide();
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
