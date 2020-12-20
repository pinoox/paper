// [parser-php]
const PINOOX = {

    // urls
    URL: {
        CURRENT: window.location.href,
        BASE: '<?php echo url("^panel"); ?>',
        APP: '<?php echo url("panel"); ?>',
        API: '<?php echo url("^api/panel/v1/"); ?>',
        SITE: '<?php echo url("~"); ?>',
        FRONT: '<?php echo url(); ?>',
        APP_PATH: '<?php echo furl(); ?>',
        PANEL: '<?php echo url("~panel"); ?>',
        THEME: '<?php echo $_url; ?>',
        AVATAR: '<?php echo furl("resources/avatar.png"); ?>',
        APP_ICON: '<?php echo furl("resources/default.png"); ?>',
    },

    LANG: <?php echo @$_lang; ?>,
};