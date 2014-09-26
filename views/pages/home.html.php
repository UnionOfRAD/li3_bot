<?php

$nickRgb = function($nick) {
	$hash = abs(crc32($nick));

	$rgb = array($hash % 255, $hash % 255, $hash % 255);
	$rgb[$hash % 2] = 0;

	return $rgb;
};

?>
<article class="home">
	<section class="logs">
		<h1 class="h-beta">Channel Logs</h1>
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
	</section>

	<section class="tells">
		<h1 class="h-beta">Most Recent Tells</h1>
		<?php if ($tells): ?>
			<dl class="tells">
			<?php foreach ($tells as $key => $value): ?>
				<dt><?php echo $key ?></dt><dd><?php echo $value ?></dd>
			<?php endforeach; ?>
			</dl>
			[<?php echo $this->html->link('View all tells…', array(
				'library' => 'li3_bot',
				'controller' => 'tells', 'action' => 'index'
			)); ?>]
		<?php else: ?>
			No tells, yet.
		<?php endif; ?>
	</section>

	<section class="karmas">
		<h1 class="h-beta">Karma Highscore</h1>
		<?php if ($karmas): ?>
			<table class="karma">
				<tr>
					<th>score</th>
					<th>nick</th>
				</tr>
			<?php foreach ($karmas as $key => $value): ?>
				<tr>
					<td><?php echo $value ?></td>
					<td class="user" style="color: rgb(<?=implode(',' , $nickRgb($key))?>);"><?php echo $key ?></td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php else: ?>
			No karma data.
		<?php endif; ?>
	</section>

	<section class="plugins">
		<?php if ($plugins): ?>
			<?php foreach ($plugins as &$plugin): ?>
				<?php
					$plugin = explode('\\', $plugin);
					$plugin = end($plugin);
				?>
			<?php endforeach; ?>
			This bot is running with plugins <?php echo implode(', ', $plugins); ?>.
		<?php endif; ?>
	</section>
</article>