<article class="logs-view">
	<h1 class="h-alpha"><?= $this->title("{$channel} Channel Logs for " . date('m/d/Y', strtotime($date))); ?></h1>

	<table class="messages">
	<?php foreach ($messages as $i => $line): ?>
		<?=$this->view()->render(
			array('element' => 'message'),
			array('id' => $i, 'item' => $line) + compact('channel', 'date', 'rewriters'),
			array('library' => 'li3_bot')
		); ?>
	<?php endforeach; ?>
	</table>

	<nav class="nav-paging">
		<?php if ($previous)
			echo $this->html->link('&larr; previous', array(
				'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'view',
				'date' => $previous,
				'channel' => ltrim($channel, '#')
			), array('rel' => 'prev', 'escape' => false));
		?>
		<?php if ($next)
			echo $this->html->link('next &rarr;', array(
				'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'view',
				'date' => $next,
				'channel' => ltrim($channel, '#')
			), array('rel' => 'next', 'escape' => false));
		?>
	</nav>
</article>