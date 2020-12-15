<?php if (isset($slides) && !empty($slides)) { ?>
    <div class="slider">
        <div class="owl-slider owl-carousel owl-theme">
            <?php foreach ($slides as $s) { ?>
                <a href="<?php echo postLink($s); ?>" class="item">
                    <img src="<?php echo showImage($s['image_id'], 512); ?>"
                         alt="<?php echo $s['title'] ?>">
                    <div class="content">
                        <div class="title"><?php echo $s['title'] ?></div>
                        <span class="publish-date"><?php showDate($s['insert_date']); ?></span>
                    </div>
                    <div class="mask"></div>
                </a>
            <?php } ?>
        </div>
    </div>
<?php } ?>