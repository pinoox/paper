<div class="page-content">
    <div class="contact">
        <div class="row">
            <div class="col-sm-5 offset-sm-1">

                <h2 class="caption"><?php lang('front.contact_us'); ?></h2>
                <small><?php lang('front.contact_us_description'); ?></small>

                <form id="form-sendMessage" class="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input name="full_name" type="text" class="pin-input" placeholder="<?php lang('front.full_name'); ?>">
                    </div>
                    <div class="form-group">
                        <input name="email" type="text" class="pin-input" placeholder="<?php lang('front.email'); ?>">
                    </div>
                    <div class="form-group">
                        <input name="subject" type="text" class="pin-input" placeholder="<?php lang('front.subject'); ?>">
                    </div>
                    <div class="form-group">
                        <textarea name="message" cols="30" rows="5" class="pin-input" placeholder="<?php lang('front.message'); ?>"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="pin-btn pin-primary"><?php lang('front.send_message') ?></button>
                    </div>
                    <div class="form-group">
                        <div id="message"></div>
                    </div>

                </form>
            </div>
            <div class="col-md-4 offset-md-1 col-sm-12 d-none d-sm-block">
                <img class="image-envelope" src="<?php echo $_url ?>dist/images/envelope.svg" alt="envelope">
            </div>
        </div>
    </div>

</div>

