function deleteComment(elm, id) {
    modalConfirm(function () {
        var element = elm.parentElement.parentElement;
        $.ajax({
            url: PINOOX.URL.APP + "comment/delete",
            type: 'POST',
            data: {
                comment_id: id
            },
            dataType: 'JSON',
            success: function (json) {
                if (statusResponse(json)) {
                    element.remove();
                }
            }
        });
    });
}

var formData = {page: 1, keyword: ''};

function loadComments() {
    $.ajax({
        url: PINOOX.URL.APP + 'comment/',
        type: 'POST',
        data: formData,
        dataType: 'HTML',
        success: function (html) {
            if (html) {
                var result = $('<div />').append(html).find('#list-comments').html();
                $('#list-comments').html(result);
            }
        }
    });
}

function paginate(page) {
    formData.page = page;
    loadComments();
}

$('#input-search').keyup(function () {
    formData.keyword = $(this).val();
    delay(function () {
        loadComments();
    }, 500);

});

function updateStatus(elm, commentId) {
    $.ajax({
        url: PINOOX.URL.APP + 'comment/updateStatus/' + commentId,
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            if (data.status) {
                if (data.result.cmStatus == 'publish') {
                    $(elm).find('i').removeClass('fa fa-check').addClass('fa fa-comment-slash');
                } else {
                    $(elm).find('i').removeClass('fa fa-comment-slash').addClass('fa fa-check');
                }
                $(elm).parent().prev().html('<span class="status ' + data.result.cmStatus + '" title="' + data.result.title + '"><i class="fa fa-circle"></i></span>');
            }
        }
    });
}