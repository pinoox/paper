<div class="page editUser">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.edit_user') ?></h2>
        </div>
        <div class="box-content">
            <form class="form" id="form-editUser" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" value="<?php echo $user['user_id'] ?>" id="user_id">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="fname" type="text" placeholder="<?php lang('panel.first_name') ?>"
                                       class="input-form"
                                       value="<?php echo $user['fname'] ?>"
                                       data-rule="required|name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="lname" type="text" placeholder="<?php lang('panel.last_name') ?>"
                                       class="input-form"
                                       value="<?php echo $user['lname'] ?>"
                                       data-rule="required|lastname">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="email" type="text" placeholder="<?php lang('panel.email') ?>"
                                       class="input-form"
                                       value="<?php echo $user['email'] ?>"
                                       data-rule="required|email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="password" type="password" placeholder="<?php lang('panel.password') ?>"
                                       class="input-form"
                                       data-rule="minlength-6">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select name="status" class="input-form">
                                    <option <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>
                                            value="active"><?php lang('panel.active') ?></option>
                                    <option <?php echo $user['status'] == 'suspend' ? 'selected' : ''; ?>
                                            value="suspend"><?php lang('panel.suspend') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <div class="thumbnail-place">
                            <div class="choose" <?php echo $isPreview ? 'style="display:none;"' : ''; ?> >
                                <span class="text"><?php lang('panel.upload_profile_image'); ?></span>
                                <span class="select-image btn btn-outline-light"
                                      onclick="document.getElementById('imageFile').click();"><i
                                            class="fa fa-image"></i> <?php lang('panel.choose_image'); ?></span>

                                <input name="image" id="imageFile" type="file" style="display:none;"/>
                            </div>
                            <div class="preview" <?php echo $isPreview ? 'style="display:block;"' : ''; ?>>
                                        <span class="btn btn-outline-secondary"
                                              onclick="document.getElementById('imageFile').click();">
                                            <i class="fa fa-refresh"></i> <?php lang('panel.change'); ?></span>
                                <span class="btn btn-outline-danger" onclick="deleteImage();">
                                            <i class="fa fa-times"></i> <?php lang('panel.delete'); ?></span>
                                <img id="img-preview" src="<?php echo @$user['image'] ?>"/>
                            </div>
                            <input type="hidden" name="isDeleteImage" id="isDeleteImage" value="false">
                        </div>
                    </div>
                </div>
                <div class="fab-wrapper">
                    <button type="submit" class="fab-btn green-gradient"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $_app; ?>user" class="fab-btn primary-gradient"><i class="fa fa-list"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>

