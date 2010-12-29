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

<div class="tells">
	<h3>Most Recent <?=$this->html->link('Tells', array(
		'library' => 'li3_bot',
		'controller' => 'tells', 'action' => 'index'
	)); ?></h3>
	<dl class="tells">
	<?php foreach ($tells as $key => $value): ?>
		<dt><?php echo $key ?></dt><dd><?php echo $value ?></dd>
	<?php endforeach; ?>
	</dl>
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