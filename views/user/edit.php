<?php $this->setLayoutVar('title', 'ユーザ情報編集') ?>

<h2>ユーザ情報編集</h2>

<form action="<?php echo $base_url; ?>/update/user" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

    <?php if(isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
    <?php endif; ?>
    <?php echo $this->render('account/inputs', array('user_name' => $user['user_name'], 'password' => '')); ?>
    <p>
        <input type="submit" value="編集" />
    </p>
</form>

<form action="<?php echo $base_url; ?>/delete/user" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
    <p>
        <input type="submit" value="削除" />
    </p>
</form>