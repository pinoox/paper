<!-- Modal -->
<form class="form" id="form-addPage">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php lang('panel.add_new_page'); ?></h5>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
                </div>
                <div class="modal-body">

                    <input type="text" class="input-form" placeholder="<?php lang('panel.page_key'); ?>"
                           name="page_key"
                           data-rule="required|minlength-2">

                </div>
                <div class="modal-footer">
                    <a class="btn primary-outline" data-dismiss="modal"><?php lang('panel.cancel'); ?></a>
                    <button id="btnAddPage" type="submit" class="btn primary-gradient"><?php lang('panel.continue'); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>