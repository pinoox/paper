<form id="cm-form-<?php echo isset($parent_id) && !empty($parent_id) ? $parent_id : 'new'; ?>"
      class="form"
      method="post"
      enctype="multipart/form-data">


    <input type="hidden" id="reply_id" name="parent_id" value="<?php echo isset($parent_id) ? $parent_id : null; ?>">
    <?php if(isLoggedIn()){?>
        <div class="form-group user-info">
            <img src="<?php echo showImage(getUser('avatar_id'),128,true); ?>" alt="<?php echo getUser('full_name'); ?>">
            <span class=""><?php echo getUser('full_name'); ?></span>
        </div>
    <?php }else{?>
        <div class="form-group">
            <input class="pin-input" type="text" name="full_name" id="full_name"
                   placeholder="<?php lang('front.full_name'); ?>">
        </div>
        <div class="form-group">
            <input class="pin-input" type="text" name="email" id="email" placeholder="<?php lang('front.email'); ?>">
        </div>
    <?php }?>

    <div class="form-group">
            <textarea class="pin-input" name="message" id="message" rows="6"
                      placeholder="<?php lang('front.message'); ?>"></textarea>
    </div>
    <button type="sumbit" class="btn-send"><?php lang('front.send'); ?></button>
    <?php if (isset($parent_id) && !empty($parent_id)) { ?>
        <span data-action="cancelReply" class="btn-cancel"><?php lang('front.cancel_reply'); ?></span>
    <?php } ?>
    <br>
    <br>
    <div id="cm-message"></div>

</form>