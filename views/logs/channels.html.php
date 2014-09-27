<article class="logs-channels">
	<h1 class="h-alpha"><?= $this->title('Channel Logs'); ?></h1>
	<ul class="channels">
		<?php foreach ((array) $channels as $channel): ?>
			 <li><?=$this->html->link($channel, array(
					'library' => 'li3_bot',
					'controller' => 'logs', 'action' => 'index',
					'year' => date('Y'),
					'channel' => ltrim($channel, '#')
			)); ?></li>
		<?php endforeach;?>
	</ul>
</article>