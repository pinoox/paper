<?php if (isset($slides) && !empty($slides)) { ?>
    <div class="slider">
        <div class="owl-slider owl-carousel owl-theme">
            <?php foreach ($slides as $s) { ?>
                <div class="item">
                    <img src="<?php echo showImage($s['image_id'], 512); ?>"
                         alt="<?php echo $s['title'] ?>">
                    <div class="content">
                        <a class="title" href="<?php echo postLink($s); ?>"><?php echo $s['title'] ?></a>
                        <span class="publish-date"><?php showDate($s['insert_date']); ?></span>
                    </div>
                    <div class="mask"></div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>