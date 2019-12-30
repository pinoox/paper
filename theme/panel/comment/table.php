<table class="table table-hover v-align-top">
    <thead>
    <tr>
        <th><?php lang('panel.row'); ?></th>
        <th><?php lang('panel.full_name'); ?></th>
        <th><?php lang('panel.email'); ?></th>
        <th><?php lang('panel.article'); ?></th>
        <th><?php lang('comment.message'); ?></th>
        <th><?php lang('panel.insert_date'); ?></th>
        <th class="text-center"><?php lang('panel.status'); ?></th>
        <th class="text-center"><?php lang('panel.operation'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($comments) && !empty($comments)) { ?>
        <?php foreach ($comments as $cm) {
            $avatar = !empty($cm['avatar_id']) ? showImage($cm['avatar_id'], '128', true) : null;
            $fullName = !empty($cm['user_full_name']) ? $cm['user_full_name'] : $cm['full_name'];
            $email = !empty($cm['user_email']) ? $cm['user_email'] : $cm['email'];
            ?>
            <tr>
                <td><?php echo $cm['comment_id'] ?></td>
                <td>
                    <?php if ($avatar != null) { ?>
                        <img class="img-thumb" src="<?php echo $avatar; ?>" alt="avatar">
                    <?php } ?>&nbsp;
                    <?php echo $fullName ?></td>
                <td><?php echo $email ?></td>
                <td class="maxWidth100"><a target="_blank" href="<?php echo url() ?>article/<?php echo $cm['article_id']; ?>"><?php echo $cm['title']; ?></a></td>
                <td class="maxWidth200"><?php echo $cm['message'] ?></td>
                <td><?php echo showDate($cm['insert_date']); ?></td>
                <td class="text-center">
                            <span class="status <?php echo $cm['status'] ?>"
                                  title="<?php echo rlang('comment.' . $cm['status']) ?>">
                                <i class="fa fa-circle"></i>
                            </span>
                </td>
                <td class="text-center">
                    <span onclick="updateStatus(this,<?php echo $cm['comment_id']; ?>)" class="tbl-action"><i
                                class="fa fa-<?php echo $cm['status'] == 'publish' ? 'comment-slash' : 'check' ?>"></i></span>
                    <span onclick="deleteComment(this,<?php echo $cm['comment_id']; ?>)" class="tbl-action"><i
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
        <?php if (!empty($comments)) { ?>
            <?php showPage($page, 'onclick="paginate([page])"'); ?>
        <?php } ?>
    </div>
</div>
