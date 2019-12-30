<div class="page dashboard-page">
    <div class="box">
        <div class="shortcut-box">
            <div class="profile">
                <img class="img" src="<?php echo showImage(getUser('avatar_id'), 128, true); ?>">
                <div class="info">
                    <span class="fullname"><?php echo getUser('full_name'); ?></span>
                    <span class="email"><?php echo getUser('email'); ?></span>
                </div>
                <div class="actions">
                    <a href="<?php echo $_app ?>user/edit/<?php echo getUser('user_id'); ?>"><i
                                class="fa fa-user-edit"></i></a>
                </div>
            </div>

            <div class="shortcuts">
                <a href="<?php echo $_app ?>contact" class="shortcut-item">
                    <i class="fa fa-phone"></i>
                    <span class="name"><?php lang('panel.contacts'); ?></span>
                    <?php if ($contactStats['unseen'] > 0) { ?>
                        <span class="count"><?php echo $contactStats['unseen']; ?></span>
                    <?php } ?>
                </a>
                <a href="<?php echo $_app ?>article" class="shortcut-item">
                    <i class="fa fa-pencil-alt"></i>
                    <span class="name"><?php lang('panel.articles'); ?></span>
                </a>
                <a href="<?php echo $_app ?>comment" class="shortcut-item">
                    <i class="fa fa-comments"></i>
                    <span class="name"><?php lang('comment.comments'); ?></span>
                    <?php if ($commentStats['pending'] > 0) { ?>
                        <span class="count"><?php echo $commentStats['pending']; ?></span>
                    <?php } ?>
                </a>
                <a href="<?php echo $_app ?>settings" class="shortcut-item">
                    <i class="fa fa-cogs"></i>
                    <span class="name"><?php lang('panel.settings'); ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-caption"><i class="fa fa-comments"></i> <?php lang('panel.comments_stats'); ?></div>
        <div class="grid-box">
            <div class="item">
                <i class="icon fa fa-comments"></i>
                <span class="caption"><?php lang('panel.total'); ?></span>
                <b class="stat"><?php echo $commentStats['total']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa-check-circle"></i>
                <span class="caption"><?php lang('comment.publish'); ?></span>
                <b class="stat"><?php echo $commentStats['publish']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa-times-circle"></i>
                <span class="caption"><?php lang('comment.suspend'); ?></span>
                <b class="stat"><?php echo $commentStats['suspend']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa-exclamation-circle <?php echo $commentStats['pending']>0 ? 'orange-color' : ''; ?>"></i>
                <span class="caption <?php echo $commentStats['pending']>0 ? 'orange-color' : ''; ?>"><?php lang('comment.pending'); ?></span>
                <b class="stat <?php echo $commentStats['pending']>0 ? 'orange-color' : ''; ?>"><?php echo $commentStats['pending']; ?></b>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-caption"><i class="fa fa-pencil-alt"></i> <?php lang('panel.articles_stats'); ?></div>
        <div class="grid-box">
            <div class="item">
                <i class="icon fa fa-pencil-alt"></i>
                <span class="caption"><?php lang('panel.total'); ?></span>
                <b class="stat"><?php echo $articleStats['total']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa-check-circle"></i>
                <span class="caption"><?php lang('panel.published'); ?></span>
                <b class="stat"><?php echo $articleStats['publishes']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa-times-circle"></i>
                <span class="caption"><?php lang('panel.suspend'); ?></span>
                <b class="stat"><?php echo $articleStats['suspends']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa-exclamation-circle"></i>
                <span class="caption"><?php lang('panel.draft'); ?></span>
                <b class="stat"><?php echo $articleStats['drafts']; ?></b>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-caption"><i class="fa fa-users"></i> <?php lang('panel.users_stats'); ?></div>
        <div class="grid-box">
            <div class="item">
                <i class="icon fa fa-users"></i>
                <span class="caption"><?php lang('panel.total'); ?></span>
                <b class="stat"><?php echo $userStats['total']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa fa-check-circle"></i>
                <span class="caption"><?php lang('panel.active'); ?></span>
                <b class="stat"><?php echo $userStats['actives']; ?></b>
            </div>
            <div class="item">
                <i class="icon fa fa fa-times-circle"></i>
                <span class="caption"><?php lang('panel.suspend'); ?></span>
                <b class="stat"><?php echo $userStats['suspends']; ?></b>
            </div>
        </div>
    </div>
</div>