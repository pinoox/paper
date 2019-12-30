<!-- Modal -->
<form class="form" id="form-addArticle">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php lang('panel.add_new_article'); ?></h5>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </span>
                </div>
                <div class="modal-body">

                    <input type="text" class="input-form" placeholder="<?php lang('panel.article_title'); ?>"
                           name="title"
                           data-rule="required|minlength-3">

                </div>
                <div class="modal-footer">
                    <a class="btn primary-outline" data-dismiss="modal"><?php lang('panel.cancel'); ?></a>
                    <button id="btnAddArticle" type="submit" class="btn primary-gradient"><?php lang('panel.continue'); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>