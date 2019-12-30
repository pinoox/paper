<?php
$fullName = !empty($comment['user_full_name']) ? $comment['user_full_name'] :$comment['full_name'];
?>

<div class="cm-box">
    <div class="cm-header">
        <div class="user-info">
            <div class="avatar">
                <img src="<?php echo showImage(@$comment['avatar_id'], 128, true) ?>"
                     alt="<?php echo $fullName; ?>">
            </div>
            <div class="details">
                <div class="name"><?php echo $fullName; ?></div>
                <time class="date"><?php echo showDate($comment['insert_date']); ?></time>
            </div>
        </div>
        <div class="actions">
            <span data-action="replyComment">  <i class="fa fa-reply"></i></span>
        </div>
    </div>
    <div class="cm-body"><?php echo $comment['message']; ?></div>

    <div class="cm-reply">
        <?php includeView('pages>comment>form', ['parent_id' => $comment['comment_id']]); ?>
    </div>

    <?php if (isset($comment['children']) && !empty($comment['children'])) { ?>
        <div class="cm-list">
            <?php foreach ($comment['children'] as $cm) { ?>
                <?php includeView('pages>comment>box', ['comment' => $cm]); ?>
            <?php } ?>
        </div>
    <?php } ?>
</div>