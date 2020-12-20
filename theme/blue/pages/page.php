<div class="page-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="article paper-article">
                    <h1 class="title" href=""><?php echo $page['title']; ?>
                        <?php if ($page['status'] !== 'publish') { ?>
                            <a href="<?php echo url('panel/page/edit/') ?><?php echo $page['page_id']; ?>"
                               class="badge">(<?php echo rlang('panel.' . $page['status']) ?>) </a>
                        <?php } ?>
                    </h1>

                    <div class="content"><?php echo $page['context']; ?></div>

                </div>

            </div>
            <div class="col-md-3 offset-md-1">
                <?php includeView('pages>sidebar'); ?>
            </div>
        </div>

    </div>
</div>