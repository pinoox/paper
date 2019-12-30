<?php
$map = array();

####### user #######
$map['footer']['js']['user/*'][] = 'assets/src/js/components/user.js?';

####### article #######
//tiny mce
$map['footer']['js']['article/*'][] = 'assets/src/libs/tinymce/tinymce.min.js';
$map['footer']['js']['article/*'][] = 'assets/src/libs/tinymce/langs/fa_IR.js';
//select 2
$map['header']['css']['article/*'][] = 'assets/src/libs/select2/css/select2.min.css';
$map['footer']['js']['article/*'][] = 'assets/src/libs/select2/js/select2.full.min.js';
$map['footer']['js']['article/*'][] = 'assets/src/libs/select2/js/i18n/fa.js';
//article component
$map['footer']['js']['article/*'][] = 'assets/src/js/components/article.js';

####### menu #######
$map['footer']['js']['menu/*'][] = 'assets/src/js/components/menu.js';

####### comment #######
$map['footer']['js']['comment/*'][] = 'assets/src/js/components/comment.js';

####### page #######
//tiny mce
$map['footer']['js']['page/*'][] = 'assets/src/libs/tinymce/tinymce.min.js';
$map['footer']['js']['page/*'][] = 'assets/src/libs/tinymce/langs/fa_IR.js';
//page component
$map['footer']['js']['page/*'][] = 'assets/src/js/components/page.js';

####### contact #######
$map['footer']['js']['contact/*'][] = 'assets/src/js/components/contact.js';
