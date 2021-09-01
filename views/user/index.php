<?php $this->setLayoutVar('title', 'アカウント'); ?>

<h2>アカウント</h2>

<p>
    ユーザID：<a href="<?php echo $base_url ?>/user/<?php echo $this->escape($user['user_name']); ?>">
        <strong><?php echo $this->escape($user['user_name']); ?></strong>
    </a>
</p>
<p><a href="<?php echo $base_url ?>/edit/<?php echo $this->escape($user['user_name']); ?> ">編集する</a></p>

<h3>フォロー中</h3>

<?php if (count($followings) > 0): ?>
    <ul>
        <?php foreach ($followings as $following): ?>
            <li><a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($following['user_name']); ?>">
                <?php echo $this->escape($following['user_name']); ?>
            </a></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h3>お気に入り</h3>

<div id="contents">
    <?php foreach ($likes as $content): ?>
        <?php echo $this->render('content/content', array(
            'content' => $content,
            'is_like' => null,
        )); ?>
    <?php endforeach; ?>
</div>