ajaxy('#form-addMenu', function () {
    $('#btnAddMenu').attr("disabled", true);
    $.ajax({
        url: PINOOX.URL.APP + 'menu/add/',
        type: 'POST',
        data: getData("#form-addMenu"),
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (json) {
            if (messageResponse(json)) {
                redirect(PINOOX.URL.APP + 'menu/build/' + $('#menu_key').val(), 1.5);
            } else {
                $('#btnAddMenu').attr("disabled", false);
            }
        }
    });
});

function editMenu(elm, menu) {
    replacePatternByName('#modal-editMenu', {
        'menu_id': menu.menu_id,
        'title': menu.title,
        'link': menu.link,
        'icon': menu.icon,
    });
    $('#modal-editMenu').modal('toggle');
}

ajaxy('#form-editMenu', function () {
    $('#btnEditMenu').attr("disabled", true);
    $.ajax({
        url: PINOOX.URL.APP + 'menu/edit/',
        type: 'POST',
        data: getData("#form-editMenu"),
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function (json) {
            if (messageResponse(json)) {
                redirect(PINOOX.URL.APP + 'menu/build/' + $('#menu_key').val(), 1.5);
            } else {
                $('#btnEditMenu').attr("disabled", false);
            }
        }
    });
});

function deleteMenu(elm, id) {
    modalConfirm(function () {
        var element = elm.parentElement.parentElement;
        $.ajax({
            url: PINOOX.URL.APP + "menu/delete",
            type: 'POST',
            data: {
                menu_id: id
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

var formData = {page: 1};

function loadMenu() {
    $.ajax({
        url: PINOOX.URL.APP + 'menu/',
        type: 'POST',
        data: formData,
        dataType: 'HTML',
        success: function (html) {
            if (html) {
                var result = $('<div />').append(html).find('#list-menus').html();
                $('#list-menus').html(result);
            }
        }
    });
}

function paginate(page) {
    formData.page = page;
    loadMenu();
}
