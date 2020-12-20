<?php includeView('pages>slider', ['slides' => posts(setting('home.posts'))]) ?>
<div class="page-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="articles-list">
                    <h2 class="section-title"><?php lang('front.new_articles'); ?>
                    </h2>

                    <?php if (isset($posts) && !empty($posts)) { ?>
                        <?php foreach ($posts as $i) { ?>
                            <?php includeView('pages>post_row', ['post' => $i]); ?>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="paging">
                    <?php if (!empty($posts)) { ?>
                        <?php showPage($page, 'href="' . $_app . 'search/[page]/"'); ?>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-3 offset-md-1">
                <?php includeView('pages>sidebar', [
                    'mostVisited' => [],
                    'hotTags' => [],
                ]); ?>
            </div>
        </div>
    </div>
</div>