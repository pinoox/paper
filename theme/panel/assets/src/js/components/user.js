ajaxy('#form-addUser', function () {
    $.ajax({
        url: PINOOX.URL.APP + 'user/add',
        type: 'POST',
        data: getData("#form-addUser"),
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (json) {
            if (statusResponse(json)) {
            }
        }
    });
});

ajaxy('#form-editUser', function () {
    var data = getData("#form-editUser");
    data.append('image', getFileInput('imageFile'));
    $.ajax({
        url: PINOOX.URL.APP + 'user/edit/' + $('#user_id').val(),
        type: 'POST',
        data: data,
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (json) {
            if (statusResponse(json)) {
            }
        }
    });
});

function deleteUser(elm, id) {
    modalConfirm(function () {
        var element = elm.parentElement.parentElement;
        $.ajax({
            url: PINOOX.URL.APP + "user/delete",
            type: 'POST',
            data: {
                user_id: id
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

function loadUsers() {
    $.ajax({
        url: PINOOX.URL.APP + 'user/',
        type: 'POST',
        data: formData,
        dataType: 'HTML',
        success: function (html) {
            if (html) {
                var result = $('<div />').append(html).find('#list-users').html();
                $('#list-users').html(result);
            }
        }
    });
}

function paginate(page) {
    formData.page = page;
    loadUsers();
}

$('#input-search').keyup(function () {
    formData.keyword = $(this).val();
    delay(function () {
        loadUsers();
    }, 500);

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
            $('.editUser .preview').css('display', 'block');
            $('.editUser .choose').css('display', 'none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteImage() {
    $('#imageFile').val('');
    $('.editUser .preview').css('display', 'none');
    $('.editUser .choose').css('display', 'block');
    $('#isDeleteImage').val(true);
}