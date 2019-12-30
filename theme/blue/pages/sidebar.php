<div class="sidebar">
    <?php if (isset($mostVisited) && !empty($mostVisited)) { ?>
        <div class="most-visit">
            <h2 class="section-title"><?php lang('front.most_viewed_articles'); ?></h2>
            <div class="list">
                <?php foreach ($mostVisited as $i) { ?>
                    <a href="<?php echo articleLink($i); ?>">
                        <span class="title"><?php echo $i['title']; ?></span>
                        <span class="details">
                                            <span><?php echo showDate($i['insert_date']); ?></span>
                                            <span><?php echo $i['visits']; ?>&nbsp;<?php lang('front.visit') ?></span>
                                        </span>
                    </a>
                <?php } ?>

            </div>
        </div>
    <?php } ?>


    <div class="hot-tags">
        <?php if (isset($hotTags) && !empty($hotTags)) { ?>
            <h2 class="section-title"><?php lang('front.hot_tags'); ?></h2>
            <div class="list">
                <?php foreach ($hotTags as $i) { ?>
                    <a href="<?php echo $_app ?>search/?tag=<?php echo $i['tag_name']; ?>">#<?php echo $i['tag_name']; ?></a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <div class="follow-us">
        <h2 class="section-title"> <?php lang('front.follow_us'); ?></h2>
        <div class="list">
            <a target="_blank" href="<?php echo $telegram; ?>" class="telegram"><i class="fab fa-telegram-plane"></i></a>
            <a target="_blank" href="<?php echo $twitter; ?>" class="twitter"><i class="fab fa-twitter"></i></a>
            <a target="_blank" href="<?php echo $instagram; ?>" class="instagram"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</div>