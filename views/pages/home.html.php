<div class="logs">
	<h3>Channel Logs</h3>
	<?php if ($channels): ?>
		<ul class="channels">
			<?php foreach ((array) $channels as $channel): ?>
				 <li><?php echo $this->html->link("#{$channel}", array(
						'library' => 'li3_bot',
						'controller' => 'logs', 'action' => 'index',
				) + compact('channel')); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		No channel logs have yet been created.
	<?php endif; ?>
</div>

<div class="tells">
	<h3>Most Recent Tells</h3>
	<?php if ($tells): ?>
		<dl class="tells">
		<?php foreach ($tells as $key => $value): ?>
			<dt><?php echo $key ?></dt><dd><?php echo $value ?></dd>
		<?php endforeach; ?>
		</dl>
		<?php echo $this->html->link('View all tells...', array(
			'library' => 'li3_bot',
			'controller' => 'tells', 'action' => 'index'
		)); ?>
	<?php else: ?>
		No tells, yet.
	<?php endif; ?>
</div>

<div class="karmas">
	<h3>Karma Highscore</h3>
	<?php if ($karmas): ?>
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
	<?php else: ?>
		No karma data.
	<?php endif; ?>
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
	<?php endif; ?>
</div>