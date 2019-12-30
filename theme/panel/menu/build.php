<div class="page">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.menu_list_of') ?>&nbsp;<?php echo lang('panel.'.$key); ?></h2>
        </div>
        <div class="box-content table-responsive">
            <div id="menu-items">
                <input type="hidden" id="menu_key" value="<?php echo $key; ?>">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><?php lang('panel.row'); ?></th>
                        <th><?php lang('panel.menu_title'); ?></th>
                        <th><?php lang('panel.menu_link'); ?></th>
                        <th><?php lang('panel.menu_icon'); ?></th>
                        <th class="text-center"><?php lang('panel.operation'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($items) && !empty($items)) { ?>
                        <?php foreach ($items as $i) { ?>
                            <tr>
                                <td><?php echo $i['menu_id'] ?></td>
                                <td><?php echo $i['title'] ?></td>
                                <td><a target="_blank" href="<?php echo $i['link']; ?>" class="form-description text-center"><?php echo $i['link'] ?></a></td>
                                <td>
                                    <i class="<?php echo $i['icon'] ?>"></i>
                                    <span class="badge badge-light"><?php echo $i['icon'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span onclick='editMenu(this,<?php echo json_encode($i); ?>)' class="tbl-action">
                        <i class="fa fa-edit"></i></span>
                                    <span onclick="deleteMenu(this,<?php echo $i['menu_id']; ?>)" class="tbl-action">
                        <i class="fa fa-trash"></i></span>

                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <?php tableEmpty(); ?>
                    <?php } ?>
                    </tbody>
                </table>

                <?php modalConfirm(); ?>
            </div>
        </div>
    </div>
</div>


<div class="fab-wrapper">
        <span data-toggle="modal" data-target="#modal-addMenu" data- class="fab-btn green-gradient"><i
                    class="fa fa-plus"></i></span>

    <a href="<?php echo $_app ?>menu" data- class="fab-btn primary-gradient"><i
                class="fa fa-list"></i></a>


</div>


<?php includeView('menu>add_menu_modal') ?>
<?php includeView('menu>edit_menu_modal') ?>


