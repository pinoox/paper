
var formData = {page: 1, keyword: ''};

function loadContacts() {
    $.ajax({
        url: PINOOX.URL.APP + 'contact/',
        type: 'POST',
        data: formData,
        dataType: 'HTML',
        success: function (html) {
            if (html) {
                var result = $('<div />').append(html).find('#list-contacts').html();
                $('#list-contacts').html(result);
            }
        }
    });
}

function paginate(page) {
    formData.page = page;
    loadContacts();
}

$('#input-search').keyup(function () {
    formData.keyword = $(this).val();
    delay(function () {
        loadContacts();
    }, 500);

});

function updateStatus(elm, id) {
    $.ajax({
        url: PINOOX.URL.APP + 'contact/updateStatus/' + id,
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            if (data.status) {
                if (data.result.contactStatus == 'seen') {
                    $(elm).find('i').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
                } else {
                    $(elm).find('i').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
                }
                $(elm).parent().prev().html('<span class="status ' + data.result.contactStatus + '" title="' + data.result.title + '"><i class="fa fa-circle"></i></span>');
            }
        }
    });
}

function deleteContact(elm, id) {
    modalConfirm(function () {
        var element = elm.parentElement.parentElement;
        $.ajax({
            url: PINOOX.URL.APP + "contact/delete",
            type: 'POST',
            data: {
                contact_id: id
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