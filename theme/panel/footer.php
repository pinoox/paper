</div>
<footer class="footer">
    <a target="_blank" href="https://www.pinoox.com">
        <img class="pinoox-logo" src="<?php echo $_url ?>assets/src/images/logo-256.png" alt="pinoox logo">
    </a>
    <div class="pinoox-copyright"><?php lang('panel.pinoox_copyright'); ?></div>
</footer>

<script src="<?php echo $_url; ?>assets/src/libs/jquery/jquery-3.3.1.min.js"></script>
<script src="<?php echo $_url; ?>assets/src/libs/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?php echo $_url; ?>assets/src/libs/underscore/underscore-min.js"></script>
<script src="<?php echo $_url; ?>assets/src/libs/js-form-validator/js-form-validator.min.js"></script>
<script src="<?php echo $_url; ?>assets/src/libs/lobibox/notifications.min.js"></script>
<script src="<?php echo $_app; ?>assets/src/js/pinoox.js"></script>
<script src="<?php echo $_url; ?>assets/src/js/main.js"></script>
<?php if (isset($_map_footer)) echo $_map_footer; ?>
</body>
</html>