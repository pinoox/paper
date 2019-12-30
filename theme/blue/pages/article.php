<div class="page-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="article">
                    <h1 class="title" href=""><?php echo $article['title']; ?>
                        <?php if ($article['status'] !== 'publish') { ?>
                            <a href="<?php echo url('panel/article/edit/') ?><?php echo $article['article_id']; ?>"
                               class="badge">(<?php echo rlang('panel.' . $article['status']) ?>) </a>
                        <?php } ?>
                    </h1>

                    <div class="details">
                        <div class="author">
                            <img src="<?php echo showImage($article['avatar_id'], 128, true); ?>"
                                 alt="<?php echo $article['title']; ?>">
                            <span><?php echo $article['full_name']; ?></span>
                        </div>
                        <div class="publish-date"><?php echo showDate($article['insert_date']); ?></div>
                        <div><?php echo $article['visits'] + 1; ?>&nbsp;<?php lang('front.visit') ?></div>
                    </div>
                    <div class="image">
                        <img src="<?php echo showImage($article['image_id']); ?>"
                             alt="<?php echo $article['title']; ?>">
                    </div>
                    <div class="content"><?php echo $article['content']; ?></div>
                    <div class="tags">

                        <?php if (isset($tags) && !empty($tags)) { ?>
                            <b><i class="fa fa-tags"></i> <?php lang('front.tags'); ?></b>
                            <?php foreach ($tags as $tag) { ?>
                                <a href="<?php echo $_app ?>search/?tag=<?php echo $tag['tag_name'] ?>">#<?php echo $tag['tag_name'] ?></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="share">
                        <b><i class="fa fa-share-alt"></i> <?php lang('front.share'); ?></b>
                        <a href="<?php echo socialLink('telegram', $article); ?>" class="telegram"><i
                                    class="fab fa-telegram-plane"></i></a>
                        <a href="<?php echo socialLink('twitter', $article); ?>" class="twitter"><i
                                    class="fab fa-twitter"></i></a>
                        <a href="<?php echo socialLink('linkedin', $article); ?>" class="linkedin"><i
                                    class="fab fa-linkedin"></i></a>
                        <a href="<?php echo socialLink('whatsapp', $article); ?>" class="whatsapp"><i
                                    class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

            </div>
            <div class="col-md-3 offset-md-1">
                <?php includeView('pages>sidebar', ['mostVisited' => $mostVisited]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <?php includeView('pages>comment/list', ['article_id' => $article['article_id']]); ?>
            </div>
        </div>
    </div>
</div>