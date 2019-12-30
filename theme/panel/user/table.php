<table class="table table-hover">
    <thead>
    <tr>
        <th><?php lang('panel.row'); ?></th>
        <th><?php lang('panel.image'); ?></th>
        <th><?php lang('panel.full_name'); ?></th>
        <th><?php lang('panel.email'); ?></th>
        <th><?php lang('panel.insert_date'); ?></th>
        <th class="text-center"><?php lang('panel.status'); ?></th>
        <th class="text-center"><?php lang('panel.operation'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($users) && !empty($users)) { ?>
        <?php foreach ($users as $u) { ?>
            <tr>
                <td><?php echo $u['user_id'] ?></td>
                <td><img class="img-thumb" src="<?php echo showImage($u['avatar_id'],'128',true); ?>" alt="image"></td>
                <td><?php echo $u['fname'] . ' ' . $u['lname'] ?></td>
                <td><?php echo $u['email'] ?></td>
                <td><?php echo showDate($u['register_date']); ?></td>
                <td class="text-center">
                            <span class="status <?php echo $u['status'] ?>"
                                  title="<?php echo rlang('panel.' . $u['status']) ?>">
                                <i class="fa fa-circle"></i>
                            </span>
                </td>
                <td class="text-center">
                    <a href="<?php echo $_app ?>user/edit/<?php echo $u['user_id'] ?>" class="tbl-action"><i
                                class="fa fa-edit"></i></a>
                    <span onclick="deleteUser(this,<?php echo $u['user_id']; ?>)" class="tbl-action"><i
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
        <?php if (!empty($users)) { ?>
            <?php showPage($page, 'onclick="paginate([page])"'); ?>
        <?php } ?>
    </div>
</div>
