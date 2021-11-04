<?php

return array(
    'nodes' => [
        'panel/dashboard',
        'panel/user',
        'panel/permission',
        'panel/write',
        'post/stats',
        'panel/comment',
        'panel/contact',
        'panel/pages',
        'panel/category',
        'panel/templates',
        'panel/profile',
        'panel/setting',
    ],
    'branches' =>
        array(
            0 =>
                array(
                    'id' => 'panel',
                    'type' => 'module',
                    'status' => 2,
                ),
            1 =>
                array(
                    'id' => 'panel/dashboard',
                    'api' => 'api/panel/v1/dashboard',
                    'status' => 2,
                ),
            2 =>
                array(
                    'id' => 'panel/user',
                    'api' => 'api/panel/v1/user',
                    'status' => 2,
                ),
            3 =>
                array(
                    'id' => 'panel/group',
                    'api' => 'api/panel/v1/group',
                    'status' => 2,
                ),
            4 =>
                array(
                    'id' => 'panel/permission',
                    'api' => 'api/panel/v1/permission',
                    'status' => 2,
                ),
            5 =>
                array(
                    'id' => 'panel/posts',
                    'api' => 'api/panel/v1/post',
                    'status' => 2,
                ),
            6 =>
                array(
                    'id' => 'panel/write',
                    'api' => 'api/panel/v1/post/save',
                    'status' => 2,
                ),
            7 =>
                array(
                    'id' => 'post/stats',
                    'api' =>
                        array(
                            0 => 'api/panel/v1/post/hasStats',
                            1 => 'api/panel/v1/post/getStats',
                            2 => 'api/panel/v1/post/getMonthly',
                        ),
                    'status' => 2,
                ),
            8 =>
                array(
                    'id' => 'all_posts',
                    'type' => 'option',
                    'status' => 2,
                ),
            9 =>
                array(
                    'id' => 'panel/comment',
                    'api' => 'api/panel/v1/comment',
                    'status' => 2,
                ),
            10 =>
                array(
                    'id' => 'panel/contact',
                    'api' => 'api/panel/v1/contact',
                    'status' => 2,
                ),
            11 =>
                array(
                    'id' => 'panel/pages',
                    'api' => 'api/panel/v1/page',
                    'status' => 2,
                ),
            12 =>
                array(
                    'id' => 'panel/category',
                    'api' => 'api/panel/v1/category',
                    'status' => 2,
                ),
            13 =>
                array(
                    'id' => 'panel/templates',
                    'api' => 'api/panel/v1/template',
                    'status' => 2,
                ),
            14 =>
                array(
                    'id' => 'panel/profile',
                    'api' => 'api/panel/v1/profile',
                    'status' => 2,
                ),
            15 =>
                array(
                    'id' => 'panel/setting',
                    'api' => 'api/panel/v1/setting',
                    'status' => 2,
                ),
        ),
);
