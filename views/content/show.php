<?php $this->setLayoutVar('title', $content['user_name']) ?>
<?php echo $this->render('content/content', array(
    'content' => $content,
    'is_like' => $is_like,
    )); ?>