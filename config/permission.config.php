<?php

const api_v1 = 'api/panel/v1/';

return [
    'module' => [
        'panel' => false,
        'panel/dashboard' => false,
        'panel/user' => false,
        'panel/group' => false,
        'panel/permission' => false,
        'panel/posts' => false,
        'panel/write' => false,
        'panel/post/stats' => false,
        'panel/comment' => false,
        'panel/contact' => false,
        'panel/page' => false,
        'panel/category' => false,
        'panel/templates' => false,
        'panel/profile' => false,
        'panel/setting' => false,
    ],
    'option' => [
        'all_posts' => false,
    ],
    'api' => [
        api_v1 => true,
        api_v1 . 'dashboard' => false,
        api_v1 . 'user' => false,
        api_v1 . 'group' => false,
        api_v1 . 'permission' => false,
        api_v1 . 'post/save' => false,
        api_v1 . 'post/hasStats' => false,
        api_v1 . 'post/getStats' => false,
        api_v1 . 'post/getMonthly' => false,
        api_v1 . 'comment' => false,
        api_v1 . 'contact' => false,
        api_v1 . 'page' => false,
        api_v1 . 'category' => false,
        api_v1 . 'template' => false,
        api_v1 . 'profile' => false,
        api_v1 . 'setting' => false,
    ],
];