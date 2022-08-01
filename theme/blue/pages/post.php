<div class="page-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="article">
                    <h1 class="title" href=""><?php echo $post['title']; ?>
                        <?php if ($post['status'] !== 'publish') { ?>
                            <a href="<?php echo url('panel/write/') ?><?php echo $post['post_id']; ?>"
                               class="badge">(<?php lang('post.status.' . $post['status']) ?>) </a>
                        <?php } ?>
                    </h1>

                    <div class="details">
                        <div class="author">
                            <img src="<?php echo $post['avatar']; ?>"
                                 alt="<?php echo $post['title']; ?>">
                            <span><?php echo $post['full_name']; ?></span>
                        </div>
                        <div class="publish-date"><?php showDate($post['publish_date']); ?></div>
                        <div><?php echo $post['visits']; ?>&nbsp;<?php lang('front.visit') ?></div>
                    </div>
                    <div class="image">
                        <img src="<?php echo $post['image']; ?>"
                             alt="<?php echo $post['title']; ?>">
                    </div>
                    <div class="content paper-article"><?php echo $post['context']; ?></div>
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
                        <a href="<?php echo socialLink('telegram', $post); ?>" class="telegram"><i
                                    class="fab fa-telegram-plane"></i></a>
                        <a href="<?php echo socialLink('twitter', $post); ?>" class="twitter"><i
                                    class="fab fa-twitter"></i></a>
                        <a href="<?php echo socialLink('linkedin', $post); ?>" class="linkedin"><i
                                    class="fab fa-linkedin"></i></a>
                        <a href="<?php echo socialLink('whatsapp', $post); ?>" class="whatsapp"><i
                                    class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

            </div>
            <div class="col-md-3 offset-md-1">
                <?php includeView('pages>sidebar'); ?>
            </div>
        </div>
        <?php if($post['comment_status'] === 'open') {?>
        <div class="row">
            <div class="col-md-8">
                <?php includeView('pages>comment>list', ['post_id' => $post['post_id']]); ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>