<?php $this->setLayoutVar('title', 'ユーザ一覧') ?>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($user['user_name']); ?>">
                <?php echo $this->escape($user['user_name']); ?>
            </a></li>
        <?php endforeach; ?>
    </ul>