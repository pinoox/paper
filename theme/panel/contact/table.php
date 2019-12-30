<table class="table table-hover">
    <thead>
    <tr>
        <th><?php lang('panel.row'); ?></th>
        <th><?php lang('panel.full_name'); ?></th>
        <th><?php lang('panel.mobile'); ?></th>
        <th class="maxWidth100"><?php lang('panel.subject'); ?></th>
        <th class="maxWidth300"><?php lang('panel.message'); ?></th>
        <th class="text-center"><?php lang('panel.insert_date'); ?></th>
        <th class="text-center"><?php lang('panel.status'); ?></th>
        <th class="text-center"><?php lang('panel.operation'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($contacts) && !empty($contacts)) { ?>
        <?php foreach ($contacts as $c) { ?>
            <tr>
                <td><?php echo $c['contact_id'] ?></td>
                <td><?php echo $c['full_name'] ?></td>
                <td><?php echo $c['mobile'] ?></td>
                <td><?php echo $c['subject'] ?></td>
                <td><?php echo $c['message'] ?></td>
                <td class="text-center gray-color"><?php echo showDate($c['insert_date']); ?></td>
                <td class="text-center">
                            <span class="status <?php echo $c['status'] ?>"
                                  title="<?php echo rlang('panel.' . $c['status']) ?>">
                                <i class="fa fa-circle"></i>
                            </span>
                </td>
                <td class="text-center">
                        <span onclick="updateStatus(this,<?php echo $c['contact_id']; ?>)" class="tbl-action"><i
                                    class="fa fa-<?php echo $c['status'] == 'seen' ? 'eye-slash' : 'eye' ?>"></i></span>

                    <span title="<?php lang('panel.delete'); ?>"
                          onclick="deleteContact(this,<?php echo $c['contact_id']; ?>)" class="tbl-action"><i
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
        <?php if (!empty($contacts)) { ?>
            <?php showPage($page, 'onclick="paginate([page])"'); ?>
        <?php } ?>
    </div>
</div>
