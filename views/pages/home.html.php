<div class="logs">
	<h3>Channel Logs</h3>
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
	<h3>Most Recent Tells</h3>
	<dl class="tells">
	<?php foreach ($tells as $key => $value): ?>
		<dt><?php echo $key ?></dt><dd><?php echo $value ?></dd>
	<?php endforeach; ?>
	</dl>
	<?=$this->html->link('View all tells...', array(
		'library' => 'li3_bot',
		'controller' => 'tells', 'action' => 'index'
	)); ?>
</div>

<div class="karmas">
	<h3>Karma Highscore</h3>
	<table class="karma">
		<tr>
			<th>score</th>
			<th>nick</th>
		</tr>
	<?php foreach ($karmas as $key => $value): ?>
		<tr>
			<td><?php echo $value ?></td>
			<td><?php echo $key ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
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