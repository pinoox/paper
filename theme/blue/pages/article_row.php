<div class="article-row">
    <div class="image">
        <a href="<?php echo articleLink($article); ?>"><img
                src="<?php echo showImage($article['image_id']) ?>"
                alt="<?php echo $article['title'] ?>"></a>
    </div>
    <div class="content">
        <h3 class="title">
            <a href="<?php echo articleLink($article); ?>"><?php echo $article['title'] ?></a>
        </h3>
        <p class="summary"><?php echo \pinoox\component\HelperString::truncateText($article['summary'], 500, true); ?></p>
        <div class="details">
            <div class="author">
                <img src="<?php echo showImage($article['avatar_id'],128,true); ?>"
                     alt="<?php echo $article['full_name']; ?>">
                <span><?php echo $article['full_name']; ?></span>
            </div>
            <div class="publish-date"><?php echo showDate($article['insert_date']); ?></div>
            <div><?php echo $article['visits']; ?>&nbsp;<?php lang('front.visit') ?></div>
        </div>
    </div>
</div>