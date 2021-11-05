<?php
//pinoox config file, generated at "2021-11-05 20:47"

return array (
    'nodes' =>
        array (
            0 => 'panel/dashboard',
            1 => 'panel/user',
            2 => 'panel/permission',
            3 => 'panel/write',
            4 => 'post/stats',
            5 => 'all_posts',
            6 => 'panel/comment',
            7 => 'panel/contact',
            8 => 'panel/pages',
            9 => 'panel/category',
            10 => 'panel/templates',
            11 => 'panel/profile',
            12 => 'panel/setting',
        ),
    'branches' =>
        array (
            0 =>
                array (
                    'id' => 'panel',
                    'text' => 'پنل مدیریت',
                    'type' => 'module',
                    'status' => 2,
                ),
            1 =>
                array (
                    'id' => 'panel/dashboard',
                    'text' => 'داشبورد',
                    'api' => 'api/panel/v1/dashboard',
                    'status' => 2,
                ),
            2 =>
                array (
                    'id' => 'panel/user',
                    'text' => 'کاربران',
                    'api' => 'api/panel/v1/user',
                    'status' => 2,
                ),
            3 =>
                array (
                    'id' => 'panel/group',
                    'text' => 'گروه های کاربری',
                    'api' => 'api/panel/v1/group',
                    'status' => 2,
                ),
            4 =>
                array (
                    'id' => 'panel/permission',
                    'text' => 'سطح دسترسی',
                    'api' => 'api/panel/v1/permission',
                    'status' => 2,
                ),
            5 =>
                array (
                    'id' => 'panel/posts',
                    'text' => 'نوشته ها',
                    'api' => 'api/panel/v1/post',
                    'status' => 2,
                ),
            6 =>
                array (
                    'id' => 'panel/write',
                    'text' => 'نوشته جدید',
                    'api' => 'api/panel/v1/post/save',
                    'status' => 2,
                ),
            7 =>
                array (
                    'id' => 'post/stats',
                    'text' => 'آمار نوشته',
                    'api' =>
                        array (
                            0 => 'api/panel/v1/post/hasStats',
                            1 => 'api/panel/v1/post/getStats',
                            2 => 'api/panel/v1/post/getMonthly',
                        ),
                    'status' => 2,
                ),
            8 =>
                array (
                    'id' => 'all_posts',
                    'type' => 'option',
                    'text' => 'نوشته های دیگر نویسندگان',
                    'status' => 2,
                ),
            9 =>
                array (
                    'id' => 'panel/comment',
                    'text' => 'نظرات',
                    'api' => 'api/panel/v1/comment',
                    'status' => 2,
                ),
            10 =>
                array (
                    'id' => 'panel/contact',
                    'text' => 'تماس با ما',
                    'api' => 'api/panel/v1/contact',
                    'status' => 2,
                ),
            11 =>
                array (
                    'id' => 'panel/pages',
                    'text' => 'برگه ها',
                    'api' => 'api/panel/v1/page',
                    'status' => 2,
                ),
            12 =>
                array (
                    'id' => 'panel/category',
                    'text' => 'دسته بندی',
                    'api' => 'api/panel/v1/category',
                    'status' => 2,
                ),
            13 =>
                array (
                    'id' => 'panel/templates',
                    'text' => 'قالب ها',
                    'api' => 'api/panel/v1/template',
                    'status' => 2,
                ),
            14 =>
                array (
                    'id' => 'panel/profile',
                    'text' => 'پروفایل',
                    'api' => 'api/panel/v1/profile',
                    'status' => 2,
                ),
            15 =>
                array (
                    'id' => 'panel/setting',
                    'text' => 'تنظیمات',
                    'api' => 'api/panel/v1/setting',
                    'status' => 2,
                ),
        ),
);

//end of config