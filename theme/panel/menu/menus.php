<table class="table table-hover">
    <thead>
    <tr>
        <th><?php lang('panel.menu_title'); ?></th>
        <th class="text-center"><?php lang('panel.show'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($menus) && !empty($menus)) { ?>
        <?php foreach ($menus as $k => $title) { ?>
            <tr>
                <td><?php echo $title ?></td>
                <td class="text-center">
                    <a href="<?php echo $_app ?>menu/build/<?php echo $k; ?>" class="tbl-action">
                        <i class="fa fa-eye"></i></a>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <?php tableEmpty(); ?>
    <?php } ?>
    </tbody>
</table>