// [parser-php]
const PINOOX = {

    // urls
    URL: {
        CURRENT: window.location.href,
        BASE: '<?php echo url("^"); ?>',
        APP: '<?php echo url(""); ?>',
        API: '<?php echo url("^api/v1/"); ?>',
        SITE: '<?php echo url("~"); ?>',
        THEME: '<?php echo $_url; ?>',
        AVATAR: '<?php echo furl("resources/avatar.png"); ?>',
        APP_ICON: '<?php echo furl("resources/default.png"); ?>',
    },

    LANG: <?php echo @$_lang; ?>,
    MENU: <?php echo @$_menu; ?>,
    general: <?php echo @setting_json('general'); ?>,
    contact: <?php echo @setting_json('contact'); ?>,
};