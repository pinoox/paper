<div class="page">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.users_list') ?></h2>
        </div>
        <div class="box-content table-responsive">
            <div class="form">
                <input id="input-search" type="text" class="input-form" placeholder="<?php lang('panel.search') ?>">
            </div>
            <div id="list-users">
                <?php includeView('user>table', $users) ?>
            </div>
        </div>
    </div>
</div>

<div class="fab-wrapper">
    <a href="<?php echo $_app; ?>user/add" class="fab-btn primary-gradient"><i class="fa fa-user-plus"></i></a>
</div>

<?php modalConfirm(); ?>