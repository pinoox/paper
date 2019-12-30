<div class="page">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('comment.comments_list') ?></h2>
        </div>
        <div class="box-content table-responsive">
            <div class="form">
                <input id="input-search" type="text" class="input-form" placeholder="<?php lang('panel.search') ?>">
            </div>
            <div id="list-comments">
                <?php includeView('comment>table', $comments) ?>
            </div>
        </div>
    </div>
</div>

<?php modalConfirm(); ?>