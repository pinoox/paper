<div class="footer">
    <div class="menu">
        <?php if (isset($footerMenu) && !empty($footerMenu)) { ?>
            <?php foreach ($footerMenu as $menu) { ?>
                <a href="<?php echo empty($menu['link']) ? $_app : $menu['link']; ?>">
                    <?php echo !empty($menu['icon']) ? "<i class='" . $menu['icon'] . "'></i>" : ""; ?>
                    <?php echo $menu['title']; ?>
                </a>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="copyright">
        <?php lang('front.copyright_text1'); ?> <a target="_blank" href="https://www.pinoox.com/"><?php lang('front.pinoox'); ?></a> <?php lang('front.copyright_text2'); ?>
    </div>
</div>
<a href="https://www.pinoox.com" class="none">powered by Pinoox</a>
<script src="<?php echo $_url ?>dist/main.js"></script>
</body>
</html>