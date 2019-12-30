<table class="table table-hover">
    <thead>
    <tr>
        <th><?php lang('panel.row'); ?></th>
        <th class="maxWidth100"><?php lang('panel.page_key'); ?></th>
        <th class="maxWidth100"><?php lang('panel.title'); ?></th>
        <th class="text-center"><?php lang('panel.insert_date'); ?></th>
        <th class="text-center"><?php lang('panel.visitors'); ?></th>
        <th class="text-center"><?php lang('panel.visits'); ?></th>
        <th class="text-center"><?php lang('panel.status'); ?></th>
        <th class="text-center"><?php lang('panel.operation'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($pages) && !empty($pages)) { ?>
        <?php foreach ($pages as $p) { ?>
            <tr>
                <td><?php echo $p['page_id'] ?></td>
                <td class="maxWidth100"><a target="_blank" href="<?php echo url() ?><?php echo $p['page_key']; ?>"><?php echo $p['page_key'] ?></a></td>
                <td class="maxWidth100"><?php echo $p['title'] ?></td>
                <td class="text-center gray-color"><?php echo showDate($p['insert_date']); ?></td>
                <td class="text-center gray-color"><i class="fa fa-user"></i>&nbsp;<?php echo $p['visitors']; ?></td>
                <td class="text-center gray-color"><i class="fa fa-eye"></i>&nbsp;<?php echo $p['visits']; ?></td>
                <td class="text-center">
                            <span class="status <?php echo $p['status'] ?>"
                                  title="<?php echo rlang('panel.' . $p['status']) ?>">
                                <i class="fa fa-circle"></i>
                            </span>
                </td>
                <td class="text-center">
                        <span onclick="updateStatus(this,<?php echo $p['page_id']; ?>)" class="tbl-action"><i
                                    class="fa fa-<?php echo $p['status'] == 'publish' ? 'times' : 'check' ?>"></i></span>
                    <a title="<?php lang('panel.edit'); ?>"
                       href="<?php echo $_app ?>page/edit/<?php echo $p['page_id'] ?>" class="tbl-action"><i
                                class="fa fa-edit"></i></a>
                    <span title="<?php lang('panel.delete'); ?>"
                          onclick="deletePage(this,<?php echo $p['page_id']; ?>)" class="tbl-action"><i
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
        <?php if (!empty($pages)) { ?>
            <?php showPage($page, 'onclick="paginate([page])"'); ?>
        <?php } ?>
    </div>
</div>
