<?php $this->setLayoutVar('title', 'ホーム') ?>

<h2>ホーム</h2>

<form action="<?php echo $base_url; ?>/content/post" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
    <?php if(isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
    <?php endif; ?>
    <textarea name="body" rows="2" cols="60"><?php echo $this->escape($body); ?></textarea>
    <p><input type="submit" values="発言" />
</form>

<div id="contents">
    <?php foreach ($contents as $content): ?>
        <?php echo $this->render('content/content', array(
            'content' => $content,
            'is_like' => null,
        )); ?>
    <?php endforeach; ?>
</div>