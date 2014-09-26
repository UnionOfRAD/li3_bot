<article class="logs-view">
	<?php $this->title("Logs for {$channel} on {$date}"); ?>

	<table class="messages">
	<?php foreach ($log as $i => $line): ?>
		<?=$this->view()->render(
			array('element' => 'log_row'),
			array('id' => $i, 'item' => $line) + compact('channel', 'date'),
			array('library' => 'li3_bot')
		); ?>
	<?php endforeach; ?>
	</table>

	<nav class="paging">
		<?php if ($previous)
			echo $this->html->link('&larr;', array(
				'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'view',
				'date' => $previous,
			) + compact('channel'), array('class' => 'prev', 'escape' => false));
		?>
		<?php if ($next)
			echo $this->html->link('&rarr;', array(
				'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'view',
				'date' => $next,
			) + compact('channel'), array('class' => 'next', 'escape' => false));
		?>
	</nav>
</article>