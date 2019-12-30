<!-- Modal -->
<form class="form" id="form-addMenu">
    <div class="modal fade" id="modal-addMenu" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" ><?php lang('panel.add_new_menu'); ?></h5>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="menu_key" value="<?php echo $key; ?>">

                    <input type="text" class="input-form" placeholder="<?php lang('panel.menu_title'); ?>"
                           name="title">

                    <input type="text" class="input-form" placeholder="<?php lang('panel.menu_link'); ?>"
                           name="link">

                    <input type="text" class="input-form" placeholder="<?php lang('panel.menu_icon'); ?>"
                           name="icon">
                </div>
                <div class="modal-footer">
                    <a class="btn primary-outline" data-dismiss="modal"><?php lang('panel.cancel'); ?></a>
                    <button id="btnAddMenu" type="submit" class="btn primary-gradient"><?php lang('panel.add'); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>