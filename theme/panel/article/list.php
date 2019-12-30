<div class="page">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.articles_list') ?></h2>
        </div>
        <div class="box-content table-responsive">
            <div class="form">
                <input id="input-search" type="text" class="input-form" placeholder="<?php lang('panel.search') ?>">
            </div>
            <div id="list-articles">
                <?php includeView('article>table', $articles) ?>
            </div>
        </div>
    </div>
</div>

<div class="fab-wrapper">
    <a data-toggle="modal" data-target="#addModal" class="fab-btn primary-gradient"><i class="fa fa-pencil-alt"></i></a>
</div>

<?php modalConfirm(); ?>
<?php includeView('article>add_modal') ?>