<div class="page">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.add_user') ?></h2>
        </div>
        <div class="box-content">
            <form class="form" id="form-addUser" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="col-sm-5">
                        <input name="fname" type="text" placeholder="<?php lang('panel.first_name') ?>" class="input-form"
                               data-rule="required|name">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-5">
                        <input name="lname" type="text" placeholder="<?php lang('panel.last_name') ?>" class="input-form"
                               data-rule="required|lastname">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-5">
                        <input name="email" type="text" placeholder="<?php lang('panel.email') ?>" class="input-form"
                        data-rule="required|email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-5">
                        <input name="password" type="password" placeholder="<?php lang('panel.password') ?>" class="input-form"
                               data-rule="required|minlength-6">
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

