<div class="article-row">
    <div class="image">
        <a href="<?php echo postLink($post); ?>"><img
                src="<?php echo $post['image']; ?>"
                alt="<?php echo $post['title'] ?>"></a>
    </div>
    <div class="content">
        <h3 class="title">
            <a href="<?php echo postLink($post); ?>"><?php echo $post['title'] ?></a>
        </h3>

        <p class="summary"><?php echo $post['summary']; ?></p>
        <div class="details">
            <div class="author">
                <img src="<?php echo $post['avatar']; ?>"
                     alt="<?php echo $post['full_name']; ?>">
                <span><?php echo $post['full_name']; ?></span>
            </div>
            <div class="publish-date"><?php showDate($post['publish_date']); ?></div>
            <div><?php echo $post['visits']; ?>&nbsp;<?php lang('front.visit') ?></div>
        </div>
    </div>
</div>