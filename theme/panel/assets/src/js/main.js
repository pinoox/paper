$(document).ready(function () {

    let dir = document.body.className;
    let align = dir === 'rtl' ? 'right' : 'left';

    $('.drawer-icon').on('click', function (e) {
        if ($('.sidebar').is(':visible')) {
            hideSidebar();
        } else {
            showSidebar();
        }
    });

    function showSidebar() {
        $('.sidebar').show();
        if ($(window).width() <= 768) {
            $('.page').css('padding-' + align, '120px');
            $('.sidebar').css('margin-' + align, '25px');
        } else {
            $('.page').css('padding-' + align, '0px');
            $('.sidebar').css('margin-' + align, '35px');
        }
    }

    function hideSidebar() {
        $('.sidebar').hide();
        $('.page').css('padding-' + align, '0px');
    }

    $(window).resize(function () {
        if ($(window).width() > 768) {
            showSidebar();
        } else {
            hideSidebar();

        }
    });

    $('.sidebar .item').on('click', function (e) {
        if ($(this).find('.sub').is(':visible')) {
            $(this).find('.sub').hide();
        } else {
            $(this).find('.sub').show();
        }
    });

    $(document).on('click', function (event) {
        if ($(event.target).closest('.toolbar .options .item').length == 0) {
            $(this).find('.menu-items').hide();
        } else {
            if ($(this).find('.menu-items').is(':visible')) {
                $(this).find('.menu-items').hide();
            } else {
                $(this).find('.menu-items').show();
            }
        }
    });

});


function getData(Id) {
    var formData = new FormData();
    $.each($(Id).serializeArray(), function (i, field) {
        formData.append(field.name, field.value);
    });
    return formData;
}

function resetFrom(Id) {
    $(Id).closest('form').find("input, textarea").val("");
}

function getFileInput(id) {
    var imageFile = document.getElementById(id);
    var image = imageFile.files[0];
    return image;
}

//Overriding default options
Lobibox.notify.OPTIONS = $.extend({}, Lobibox.notify.OPTIONS, {
    'class': 'animated-fast',
    mini: {
        'class': 'notify-mini',
        messageHeight: 32
    },
});

function notify(status, message) {
    Lobibox.notify(
        status,  // Available types 'warning', 'info', 'success', 'error'
        {
            title: PINOOX.LANG[status],
            msg: message,
            rounded: true,
            sound: false,
            showClass: 'fadeInRight',
            hideClass: 'bounceOut',
            delay: 5000,
        },
    );
}

function statusResponse(json) {
    if (json.status) {
        notify('success', json.result);
        return true;
    } else {
        notify('error', json.result);
        return false;
    }
}

function messageResponse(json) {
    if (json.status) {
        notify('success', json.message);
        return true;
    } else {
        notify('error', json.message);
        return false;
    }
}

// ajax request with validation form
function ajaxy(id, ajax) {
    if (document.querySelector(id) == null) return false;

    new Validator(document.querySelector(id), function (err, status) {
        if (status) {
            ajax();
        }
    }, {locale: 'fa'});
}

function replacePatternByName(id, data) {
    $.each(data, function (key, value) {
        if (value == null) value = '';
        $(id).find('input[name="' + key + '"]').val(value);
    });
}


var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();


function redirect(path, seconds) {
    var s = seconds * 1000;
    setTimeout(function () {
        window.location = path;
    }, s);
}
