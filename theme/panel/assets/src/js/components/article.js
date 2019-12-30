ajaxy('#form-addArticle', function () {
    $('#btnAddArticle').attr("disabled", true);
    $.ajax({
        url: PINOOX.URL.APP + 'article/add/',
        type: 'POST',
        data: getData("#form-addArticle"),
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (json) {
            if (messageResponse(json)) {
                redirect(PINOOX.URL.APP + "article/edit/" + json.result, 1.3);
            } else {
                $('#btnAddArticle').attr("disabled", false);
            }
        }
    });
});

ajaxy('#form-editArticle', function () {
    var data = getData("#form-editArticle");
    data.append('image', getFileInput('imageFile'));
    data.append('content',  tinymce.get('tiny-editor').getContent());

    $.ajax({
        url: PINOOX.URL.APP + 'article/edit/' + $('#article_id').val(),
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

function deleteArticle(elm, id) {
    modalConfirm(function () {
        var element = elm.parentElement.parentElement;
        $.ajax({
            url: PINOOX.URL.APP + "article/delete",
            type: 'POST',
            data: {
                article_id: id
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

function loadArticles() {
    $.ajax({
        url: PINOOX.URL.APP + 'article/',
        type: 'POST',
        data: formData,
        dataType: 'HTML',
        success: function (html) {
            if (html) {
                var result = $('<div />').append(html).find('#list-articles').html();
                $('#list-articles').html(result);
            }
        }
    });
}

function paginate(page) {
    formData.page = page;
    loadArticles();
}

$('#input-search').keyup(function () {
    formData.keyword = $(this).val();
    delay(function () {
        loadArticles();
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
    images_upload_url: PINOOX.URL.APP + 'article/imageUpload/' + $('#article_id').val(),
    plugins: "image link",
    toolbar: "undo redo | styleselect | bold italic | alignright alignleft aligncenter  alignjustify | outdent indent | forecolor backcolor | image link",
});

$(document).ready(function () {
    $.fn.select2.defaults.set('language', 'fa');
    $('.select-tag').select2({
        placeholder: 'برچسب ها',
        language: 'fa',
        tags: true,
        multiple: true,
        dir: 'rtl',
        width: '100%',
        tokenSeparators: [',', ' '],
    });
});

$("#imageFile").change(function () {
    readURL(this);
});

function readURL(input) {
    if (input.files && input.files[0]) {
        if (input.files[0].type.indexOf("image") == -1) {
            notify('error', PINOOX.LANG.only_image_allow);
            return false;
        }
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img-preview').attr('src', e.target.result);
            $('.editArticle .preview').css('display', 'block');
            $('.editArticle .choose').css('display', 'none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteImage() {
    $('#imageFile').val('');
    $('.editArticle .preview').css('display', 'none');
    $('.editArticle .choose').css('display', 'block');
    $('#isDeleteImage').val(true);
}

function saveAsFeature(elm,articleId) {
    $.ajax({
        url: PINOOX.URL.APP + 'article/saveAsFeature/' + articleId,
        type: 'POST',
        data: formData,
        dataType: 'JSON',
        success: function (data) {
            if(data.status){
                if($(elm).find('i').hasClass('far'))
                    $(elm).find('i').removeClass('far fa-star').addClass('fas fa-star');
                else
                    $(elm).find('i').removeClass('fas fa-star').addClass('far fa-star');
            }
        }
    });
}
function updateStatus(elm, id) {
    $.ajax({
        url: PINOOX.URL.APP + 'article/updateStatus/' + id,
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            if (data.status) {
                if (data.result.articleStatus == 'publish') {
                    $(elm).find('i').removeClass('fa fa-check').addClass('fa fa-times');
                } else {
                    $(elm).find('i').removeClass('fa fa-times').addClass('fa fa-check');
                }
                $(elm).parent().prev().html('<span class="status ' + data.result.articleStatus + '" title="' + data.result.title + '"><i class="fa fa-circle"></i></span>');
            }
        }
    });
}