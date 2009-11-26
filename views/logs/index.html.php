<?php if (empty($logs)): ?>
<p>Choose a channel to the left.</p>
<?php else: ?>
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
