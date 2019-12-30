<div class="login-page">
    <div class="bg animated slideInLeft"></div>
    <div class="title animated slideInLeft">
        <h2><?php lang('~user.login_to_account') ?></h2>
        <img src="<?php echo $_url ?>assets/src/images/logo-256.png" alt="login to account">
    </div>


    <form class="login-form  animated fadeInRight" action="<?php echo $_app ?>account/login" method="post">
        <div class="form-item animated slideInRight">
            <input type="text" placeholder="<?php lang('~user.username'); ?>" name="username" value="<?php echo $formData['username']; ?>">
            <i class="fa fa-user"></i>
        </div>
        <div class="form-item animated fast slideInRight">
            <input type="password" placeholder="<?php lang('~user.password'); ?>" name="password">
            <i class="fa fa-lock"></i>
        </div>
        <button type="submit" class="ptn ptn-secondary"><?php lang('~user.login') ?></button>
        <?php if (isset($error) && !empty($error)) { ?>
            <div class="message animated bounceIn"><?php echo $error; ?></div>
        <?php } ?>
    </form>

</div>