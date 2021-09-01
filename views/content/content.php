<div class="content">
    <div class="content_content">
        <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($content['user_name']); ?>">
            <?php echo $this->escape($content['user_name']); ?>
        </a>
        <?php echo $this->escape($content['body']); ?>
    </div>
    <div>
        <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($content['user_name']); ?>/content/<?php echo $this->escape($content['id']); ?>">
            <?php echo $this->escape($content['created_at']); ?>
        </a>
    </div>
    <?php if (!is_null($is_like)): ?>
        <?php if ($is_like): ?>
            <form action="<?php echo $base_url; ?>/unlike" method="post">
                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
                <input type="hidden" name="content_id" value="<?php echo $this->escape($content['id']); ?>" />
                <p>
                    <input type="submit" value="いいね解除" />
                </p>
            </form>
        <?php else: ?>
            <form action="<?php echo $base_url; ?>/like" method="post">
                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
                <input type="hidden" name="content_id" value="<?php echo $this->escape($content['id']); ?>" />
                <p>
                    <input type="submit" value="いいね" />
                </p>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>