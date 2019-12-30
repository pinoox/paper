ajaxy('#form-addPage', function () {
    $('#btnAddPage').attr("disabled", true);
    $.ajax({
        url: PINOOX.URL.APP + 'page/add/',
        type: 'POST',
        data: getData("#form-addPage"),
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (json) {
            if (messageResponse(json)) {
                redirect(PINOOX.URL.APP + "page/edit/" + json.result, 1.3);
            } else {
                $('#btnAddPage').attr("disabled", false);
            }
        }
    });
});

ajaxy('#form-editPage', function () {
    var data = getData("#form-editPage");
    data.append('content',  tinymce.get('tiny-editor').getContent());

    $.ajax({
        url: PINOOX.URL.APP + 'page/edit/' + $('#page_id').val(),
        type: 'POST',
        data: data,
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (json) {
            if (messageResponse(json)) {

            }
        }
    });
});

function deletePage(elm, id) {
    modalConfirm(function () {
        var element = elm.parentElement.parentElement;
        $.ajax({
            url: PINOOX.URL.APP + "page/delete",
            type: 'POST',
            data: {
                page_id: id
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

function loadPages() {
    $.ajax({
        url: PINOOX.URL.APP + 'page/',
        type: 'POST',
        data: formData,
        dataType: 'HTML',
        success: function (html) {
            if (html) {
                let result = $('<div />').append(html).find('#list-pages').html();
                $('#list-pages').html(result);
            }
        }
    });
}

function paginate(page) {
    formData.page = page;
    loadPages();
}

$('#input-search').keyup(function () {
    formData.keyword = $(this).val();
    delay(function () {
        loadPages();
    }, 500);

});
tinymce.init({
    selector: '.tiny-editor',
    directionality: 'rtl',
    language: 'fa_IR',
    automatic_uploads: true,
    relative_urls : false,
    document_base_url : PINOOX.URL.SITE,
    height: 650,
    images_upload_url: PINOOX.URL.APP + 'page/imageUpload/' + $('#page_id').val(),
    plugins: "image link",
    toolbar: "undo redo | styleselect | bold italic | alignright alignleft aligncenter  alignjustify | outdent indent | forecolor backcolor | image link",
});

function updateStatus(elm, id) {
    $.ajax({
        url: PINOOX.URL.APP + 'page/updateStatus/' + id,
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            if (data.status) {
                if (data.result.pageStatus == 'publish') {
                    $(elm).find('i').removeClass('fa fa-check').addClass('fa fa-times');
                } else {
                    $(elm).find('i').removeClass('fa fa-times').addClass('fa fa-check');
                }
                $(elm).parent().prev().html('<span class="status ' + data.result.pageStatus + '" title="' + data.result.title + '"><i class="fa fa-circle"></i></span>');
            }
        }
    });
}