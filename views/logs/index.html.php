<?php if (empty($logs)): ?>
<?php $this->title('Channels'); ?>
<ul class="channels">
	<?php foreach ((array)$channels as $channel): ?>
		  <li><?=$this->html->link('#' . $channel, 'bot/' . $channel); ?></li>
	<?php endforeach;?>
</ul>
<?php else: ?>
<?php $this->title("Logs for {$channel}"); ?>
<ul>
  <?php foreach ((array)$logs as $date): ?>
    <li>
		<?php echo $this->html->link($date, array(
			'plugin' => 'li3_bot', 'controller' => 'logs', 'action' => 'view',
			'args' => array($channel, $date)
		));?>
    </li>
  <?php endforeach;?>
</ul>
<?php endif; ?>