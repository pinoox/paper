var $ = require("jquery");
import 'popper.js';
import '../libs/bootstrap/js/bootstrap.bundle.min';
import 'owl.carousel';
import './components/comment';
import './components/contact';

$(document).ready(function () {
    let align = document.body.className === 'rtl' ? 'Right' : 'Left';
    $('.owl-slider').owlCarousel({
        rtl: true,
        margin: 50,
        items: 3,
        center: false,
        loop: false,
        stagePadding: 5,
        nav: false,
        dots: false,
        autoplay: true,
        responsive: {
            0: {items: 1,},
            480: {items: 1,},
            768: {items: 2,},
            992: {items: 3,},
        }
    });

    $('.drawer-menu').on('click', function (e) {
        if (isMenuOpen()) {
            hideMenu();
        } else {
            showMenu();
        }
    });
    $(window).resize(function () {
        if ($(window).width() > 992 && isMenuOpen())
            hideMenu();

    });
    $('.overlay').on('click', function () {
        hideMenu();
    });

    function isMenuOpen() {
        return $('.mini-menu').is(':visible');
    }

    function showMenu() {
        $('.mini-menu').addClass('animated faster slideIn' + align).show();
        $('.drawer-menu').find('i').removeClass('fas fa-bars').addClass('fa fa-times');
        $('.overlay').show();
    }

    function hideMenu() {
        $('.mini-menu').removeClass('animated faster slideIn' + align).addClass('animated faster slideOut' + align)
            .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd onAnimation', function () {
                $('.mini-menu').removeClass('animated faster slideOut' + align).hide();
            });
        $('.drawer-menu').find('i').removeClass('fa fa-times').addClass('fas fa-bars');
        $('.overlay').hide();
    }

});

$('#ic-search').on('click', function () {
    $('.search-modal').css('display', 'block');
    animateCSS('.search-modal', 'fadeIn', function () {
        $('.search-modal').css('display', 'block');
        $('.search-modal').removeClass('fadeOut');
    })
});

$('#close-search').on('click', function () {
    animateCSS('.search-modal', 'fadeOut', function () {
        $('.search-modal').css('display', 'none');
        $('.search-modal').removeClass('fadeIn');
    });
});

function animateCSS(element, animationName, callback) {
    const node = document.querySelector(element);
    node.classList.add('animated', animationName);

    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }

    node.addEventListener('animationend', handleAnimationEnd)
}

$('.drawer-category .item .arrow').on('click', function (e) {
    $(e.target).closest('.fa').toggleClass('fa-chevron-down fa-chevron-up');
    $(e.target).closest('.arrow').prev('.sub').slideToggle()
});