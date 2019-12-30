<div class="comments">
    <input type="hidden" value="<?php echo $article_id ?>" id="article_id">
    <b class="caption"><i class="fa fa-comments"></i> <?php lang('front.comments'); ?></b><span
            class="cmCount">(<?php echo $cmCount; ?>)</span>
    <hr>
    <br>
    <?php includeView('pages>comment>form', ['article_id' => $article_id]) ?>

    <div class="list">
        <?php if (isset($comments) && !empty($comments)) { ?>
            <?php foreach ($comments as $c) { ?>
                <?php includeView('pages>comment>box', ['comment' => $c, 'article_id' => $article_id]); ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>