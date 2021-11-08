<!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script type="text/javascript" src="<?php echo $_app ?>dist/pinoox.js"></script>
    <link rel="stylesheet" href="<?php echo $_url ?>dist/main.css?v=1.0">

    <?php paper_head(); ?>
</head>

<body class="<?php echo @$_direction; ?>">

<div class="search-modal animated faster fadeIn">
    <div class="container">
        <form action="<?php echo $_app ?>search/" method="get">
            <i id="close-search" class="fa fa-times"></i>
            <input name="q" class="input-search" type="search"
                   placeholder="<?php lang('front.searching_for_what_write_down_here'); ?>">
        </form>
    </div>
</div>
<div class="header">
    <div class="toolbar">
        <div class="drawer-menu"><i class="fas fa-bars"></i></div>
        <div class="logo">
            <a href="<?php echo $_app; ?>">
                <img src="<?php echo furl(setting('general.site_logo')); ?>" alt="logo">
                <div class="title">
                    <h1><?php echo setting('general.site_title'); ?></h1>
                    <h2><?php echo setting('general.site_subtitle'); ?></h2>
                </div>
            </a>
        </div>
        <div class="menu">
            <?php paper_menu(); ?>
        </div>
        <div class="left-side">
            <a href="<?php echo $_app ?>contact" class="action-item"><i class="fa fa-phone"></i></a>
            <span id="ic-search" class="action-item"><i class="fa fa-search"></i></span>
            <a href="<?php echo $_app ?>panel/account" id="ic-login" class="action-item"><i
                        class="fa fa-user<?php echo isLoggedIn() ? '-cog' : ''; ?>"></i></a>
        </div>
    </div>
    <div class="category">
        <?php $tree = category_tree(); ?>
        <?php if (!empty($tree)) { ?>
            <?php foreach ($tree as $t) { ?>
                <div class="item">
                    <a href="<?php echo url('search?cat=') . $t['cat_key'] ?>"><?php echo $t['cat_name'] ?></a>
                    <?php if (!empty($t['children'])) { ?>
                        <div class="sub">
                            <?php foreach ($t['children'] as $c) { ?>
                                <div class="item"><a href="<?php echo url('search?cat=') . $c['cat_key'] ?>"><?php echo $c['cat_name'] ?></a></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>

    </div>
</div>
<div class="mini-menu">
    <?php paper_menu(); ?>
</div>
<div class="overlay"></div>