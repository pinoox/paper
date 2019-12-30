<div class="page editPage">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.create_page'); ?></h2>
        </div>
        <div class="box-content">
            <form id="form-editPage" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">

                        <input type="hidden" id="page_id" value="<?php echo $page['page_id']; ?>">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" placeholder="<?php lang('panel.page_key'); ?>"
                                       class="input-form"
                                       data-rule="required|minlength-2"
                                       name="page_key"
                                       value="<?php echo $page['page_key'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" placeholder="<?php lang('panel.page_title'); ?>"
                                       class="input-form"
                                       name="title"
                                       value="<?php echo $page['title'] ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <select class="select-status input-form" name="status">
                                    <option <?php echo $page['status'] == 'publish' ? 'selected' : ''; ?>
                                            value="publish"><?php lang('panel.publish'); ?></option>
                                    <option <?php echo $page['status'] == 'suspend' ? 'selected' : ''; ?>
                                            value="suspend"><?php lang('panel.suspend'); ?></option>
                                    <option <?php echo $page['status'] == 'draft' ? 'selected' : ''; ?>
                                            value="draft"><?php lang('panel.draft'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea name="content" id="tiny-editor" class="tiny-editor"><?php echo $page['content'] ?></textarea>
                    </div>
                </div>

                <div class="fab-wrapper">
                    <button type="submit" class="fab-btn green-gradient"><i class="fa fa-save"></i></button>
                    <a href="<?php echo url() ?><?php echo $page['page_key']; ?>" target="_blank" class="fab-btn info-gradient"><i
                                class="fa fa-eye"></i></a>
                    <a href="<?php echo $_app; ?>page" class="fab-btn primary-gradient"><i
                                class="fa fa-list"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>