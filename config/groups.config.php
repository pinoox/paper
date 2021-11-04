<?php

return [
    'administrator' => [
        'group_key' => 'administrator',
        'group_name' => rlang('user.administrator'),
        'is_main' => true,
    ],
    'user' => [
        'group_key' => 'user',
        'group_name' => 'کاربر عادی',
        'is_main' => true,
        'hide' => true,
    ],
    'guest' => [
        'group_key' => 'guest',
        'group_name' => 'مهمان',
        'is_main' => true,
        'hide' => true,
    ],
];