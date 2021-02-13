<!doctype html>
<html lang="<?php echo $_translate; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php paper_head(); ?>

    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="<?php echo $_url; ?>dist/<?php echo @$assets['vendor_css']; ?>">
    <link rel="stylesheet" href="<?php echo $_url; ?>dist/<?php echo @$assets['main_css']; ?>">
    <script src="<?php echo url(''); ?>dist/pinoox.js?lang=<?php echo $_translate; ?>&v1"></script>
</head>

<body class="<?php echo @$_direction; ?>">

<div id="app">
</div>

<script src="<?php echo $_url; ?>dist/<?php echo @$assets['vendor_js']; ?>"></script>
<script src="<?php echo $_url; ?>dist/<?php echo @$assets['main_js']; ?>"></script>
</body>
</html>