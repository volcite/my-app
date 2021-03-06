<?php $this->setLayoutVar('title', $user['user_name']) ?>

<h2><?php echo $this->escape($user['user_name']); ?></h2>

<?php if (!is_null($following)): ?>
    <?php if ($following): ?>
        <form action="<?php echo $base_url; ?>/unfollow" method="post">
            <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
            <input type="hidden" name="following_name" value="<?php echo $this->escape($user['user_name']); ?>" />
            <input type="submit" value="フォロー解除" />
        </form>
    <?php else: ?>
        <form action="<?php echo $base_url; ?>/follow" method="post">
            <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
            <input type="hidden" name="following_name" value="<?php echo $this->escape($user['user_name']); ?>" />
            <input type="submit" value="フォロー" />
        </form>
    <?php endif; ?>
<?php endif; ?>

<div id="contents">
    <?php foreach ($contents as $content): ?>
        <?php echo $this->render('content/content', array(
            'content' => $content,
            'is_like' => null,
            )); ?>
    <?php endforeach; ?>
</div>
