<div class="sidebar">
    <?php if (false) { ?>
        <div class="most-visit">
            <h2 class="section-title"><?php lang('front.most_viewed_articles'); ?></h2>
            <div class="list">
                <?php foreach ($mostVisited as $i) { ?>
                    <a href="<?php echo postLink($i); ?>">
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
        <?php if (false) { ?>
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
            <?php foreach (setting('contact.socials') as $item) { ?>
                <a target="_blank" href="<?php echo @$item['link']; ?>" class="<?php echo @$item['label']; ?>">
                    <?php if (!empty($item['icon'])) { ?>
                        <i class="<?php echo @$item['icon']; ?>"></i>
                    <?php } else { ?>
                        <img alt="<?php echo @$item['label']; ?>" src="<?php echo furl($item['image']); ?>"/>
                    <?php } ?>
                </a>
            <?php } ?>
        </div>
    </div>
</div>