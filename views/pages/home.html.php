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
