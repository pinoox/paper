<div class="page editArticle">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.write_article'); ?></h2>
        </div>
        <div class="box-content">
            <form id="form-editArticle" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">

                        <input type="hidden" id="article_id" value="<?php echo $article['article_id']; ?>">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" placeholder="<?php lang('panel.article_title'); ?>"
                                       class="input-form"
                                       data-rule="required|minlength-2"
                                       name="title"
                                       value="<?php echo $article['title'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" placeholder="<?php lang('panel.article_summary'); ?>"
                                       class="input-form"
                                       name="summary"
                                       value="<?php echo $article['summary'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select class="select-tag" name="tags[]" multiple="multiple">
                                    <?php foreach ($tags as $t) { ?>
                                        <option selected value="<?php echo $t['tag_name']; ?>"><?php echo $t['tag_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <select class="select-status input-form" name="status">
                                    <option <?php echo $article['status'] == 'publish' ? 'selected' : ''; ?>
                                            value="publish"><?php lang('panel.publish'); ?></option>
                                    <option <?php echo $article['status'] == 'suspend' ? 'selected' : ''; ?>
                                            value="suspend"><?php lang('panel.suspend'); ?></option>
                                    <option <?php echo $article['status'] == 'draft' ? 'selected' : ''; ?>
                                            value="draft"><?php lang('panel.draft'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="thumbnail-place">
                            <div class="choose" <?php echo $isPreview ? 'style="display:none;"' : ''; ?> >
                                <span class="text"><?php lang('panel.upload_article_thumbnail'); ?></span>
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
                                <img id="img-preview" src="<?php echo @$article['image'] ?>"/>
                            </div>
                            <input type="hidden" name="isDeleteImage" id="isDeleteImage" value="false">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea name="content" id="tiny-editor" class="tiny-editor"><?php echo $article['content'] ?></textarea>
                    </div>
                </div>

                <div class="fab-wrapper">
                    <button type="submit" class="fab-btn green-gradient"><i class="fa fa-save"></i></button>
                    <a href="<?php echo url() ?>article/<?php echo $article['article_id']; ?>" target="_blank" class="fab-btn info-gradient"><i
                                class="fa fa-eye"></i></a>
                    <a href="<?php echo $_app; ?>article" class="fab-btn primary-gradient"><i
                                class="fa fa-list"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>