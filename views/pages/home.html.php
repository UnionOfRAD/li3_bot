<?php

$nickRgb = function($nick) {
	$hash = abs(crc32($nick));

	$rgb = array($hash % 255, $hash % 255, $hash % 255);
	$rgb[$hash % 2] = 0;

	return $rgb;
};

?>
<article class="home">
	<h1 class="h-alpha">IRC Bot</h1>

	<section class="logs">
		<h1 class="h-beta">Channel Logs</h1>
		<?php if ($channels): ?>
			<ul class="channels">
				<?php foreach ((array) $channels as $channel): ?>
					 <li><?php echo $this->html->link("#{$channel}", array(
							'library' => 'li3_bot',
							'controller' => 'logs', 'action' => 'index',
							'year' => date('Y')
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
			<table class="tells">
			<?php foreach ($tells as $item): ?>
				<tr>
					<td><?= $item->key ?>
					<td><?= $item->value ?></dd>
			<?php endforeach; ?>
			</table>
			<p class="view-all-tells">
			[<?php echo $this->html->link('View all tells…', array(
				'library' => 'li3_bot',
				'controller' => 'tells', 'action' => 'index'
			)); ?>]
			</p>
		<?php else: ?>
			No tells, yet.
		<?php endif; ?>
	</section>

	<section class="karmas">
		<h1 class="h-beta">Karma Highscore</h1>
		<?php if ($karmas): ?>
			<table class="karma">
			<?php foreach ($karmas as $item): ?>
				<tr>
					<td><?= $item->score ?></td>
					<td class="user" style="color: rgb(<?=implode(',' , $nickRgb($item->user))?>);"><?= $item->user ?></td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php else: ?>
			No karma data.
		<?php endif; ?>
	</section>
</article>