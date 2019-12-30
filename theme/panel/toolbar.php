<nav class="toolbar">
    <div class="drawer-icon"><i class="fas fa-bars"></i></div>
    <div class="header">
        <img src="<?php echo $_url; ?>assets/src/images/icon-paper.png" alt="pinoox logo">
        <div class="title">
            <h1><?php lang('panel.app_name'); ?></h1>
            <h2><?php lang('panel.app_description'); ?></h2>
        </div>
    </div>
    <div class="options">
        <a target="_blank" href="<?php echo url() ?>" class="item">
            <i class="icon fa fa-eye"></i>
        </a>

        <div class="item">
            <img src="<?php echo showImage(getUser('avatar_id'), 128,true); ?>" alt="avatar">
            <div class="title">
                <span class="text"><?php echo getUser('full_name'); ?></span>
                <span class="description"><?php lang('panel.admin') ?></span>
            </div>
            <div class="handler">
                <i class="fa fa-chevron-down handler"></i>
            </div>
            <div class="menu-items">
                <ul>
                    <li>
                        <a href="<?php echo $_app ?>settings"><?php lang('panel.settings'); ?></a>
                        <a href="<?php echo url() ?>panel/account/logout"><?php lang('panel.logout'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>