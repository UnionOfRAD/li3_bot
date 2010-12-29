<div class="logs">
	<h3><?=$this->html->link('Channel Logs', array(
		'library' => 'li3_bot',
		'controller' => 'logs', 'action' => 'index'
	)); ?></h3>
	<ul class="channels">
		<?php foreach ((array) $channels as $channel): ?>
			 <li><?=$this->html->link("#{$channel}", array(
					'library' => 'li3_bot',
					'controller' => 'logs', 'action' => 'index',
			) + compact('channel')); ?></li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="plugins">
	<?php if ($plugins): ?>
		<?php foreach ($plugins as &$plugin): ?>
			<?php
				$plugin = explode('\\', $plugin);
				$plugin = end($plugin);
			?>
		<?php endforeach; ?>
		This bot is running with plugins <?php echo implode(', ', $plugins); ?>.
	<? endif; ?>
</div>