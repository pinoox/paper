<table class="table table-hover">
    <thead>
    <tr>
        <th><?php lang('panel.row'); ?></th>
        <th><?php lang('panel.image'); ?></th>
        <th class="maxWidth100"><?php lang('panel.title'); ?></th>
        <th class="maxWidth300"><?php lang('panel.summary'); ?></th>
        <th class="text-center"><?php lang('panel.insert_date'); ?></th>
        <th class="text-center"><?php lang('panel.visitors'); ?></th>
        <th class="text-center"><?php lang('panel.visits'); ?></th>
        <th class="text-center"><?php lang('panel.status'); ?></th>
        <th class="text-center"><?php lang('panel.operation'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($articles) && !empty($articles)) { ?>
        <?php foreach ($articles as $a) { ?>
            <tr>
                <td><?php echo $a['article_id'] ?></td>
                <td><img class="img-thumb" src="<?php echo showImage($a['image_id'], '800'); ?>" alt="image"></td>
                <td class="maxWidth100"><a target="_blank" href="<?php echo url() ?>article/<?php echo $a['article_id']; ?>"><?php echo $a['title'] ?></a></td>
                <td class="maxWidth300"><?php echo $a['summary'] ?></td>
                <td class="text-center gray-color"><?php echo showDate($a['insert_date']); ?></td>
                <td class="text-center gray-color"><i class="fa fa-user"></i>&nbsp;<?php echo $a['visitors']; ?></td>
                <td class="text-center gray-color"><i class="fa fa-eye"></i>&nbsp;<?php echo $a['visits']; ?></td>
                <td class="text-center">
                            <span class="status <?php echo $a['status'] ?>"
                                  title="<?php echo rlang('panel.' . $a['status']) ?>">
                                <i class="fa fa-circle"></i>
                            </span>
                </td>
                <td class="text-center">
                        <span onclick="updateStatus(this,<?php echo $a['article_id']; ?>)" class="tbl-action"><i
                                    class="fa fa-<?php echo $a['status'] == 'publish' ? 'times' : 'check' ?>"></i></span>
                    <span title="<?php lang('panel.save_as_feature'); ?>"
                          onclick="saveAsFeature(this,<?php echo $a['article_id']; ?>)"
                          class="tbl-action">
                        <i class="<?php echo $a['feature'] ? 'fas' : 'far' ?> fa-star"></i>
                    </span>
                    <a title="<?php lang('panel.edit'); ?>"
                       href="<?php echo $_app ?>article/edit/<?php echo $a['article_id'] ?>" class="tbl-action"><i
                                class="fa fa-edit"></i></a>
                    <span title="<?php lang('panel.delete'); ?>"
                          onclick="deleteArticle(this,<?php echo $a['article_id']; ?>)" class="tbl-action"><i
                                class="fa fa-trash"></i></span>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <?php tableEmpty(); ?>
    <?php } ?>
    </tbody>
</table>
<div class="box-footer">
    <div class="paginate">
        <?php if (!empty($articles)) { ?>
            <?php showPage($page, 'onclick="paginate([page])"'); ?>
        <?php } ?>
    </div>
</div>
