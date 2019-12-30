<?php includeView('pages>slider', ['slides' => $features]) ?>
<div class="page-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="articles-list">
                    <h2 class="section-title"><?php lang('front.new_articles'); ?>
                    </h2>

                    <?php if (isset($newest) && !empty($newest)) { ?>
                        <?php foreach ($newest as $i) { ?>
                            <?php includeView('pages>article_row', ['article' => $i]); ?>
                        <?php } ?>
                    <?php } ?>
                </div>

            </div>
            <div class="col-md-3 offset-md-1">
                <?php includeView('pages>sidebar', [
                    'mostVisited' => $mostVisited,
                    'hotTags' => $hotTags,
                ]); ?>
            </div>
        </div>
    </div>
</div>