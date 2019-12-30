// [parser-php]
var PINOOX = {

    URL: {
        SITE: '<?php echo $_site; ?>',
        APP: '<?php echo $_app; ?>',
    },

    LANG: <?php echo json_encode($_lang, JSON_FORCE_OBJECT); ?>,

};