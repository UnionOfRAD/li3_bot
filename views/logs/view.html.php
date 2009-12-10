<table class="messages">
<?php foreach ($log as $i => $line): ?>
	<?php
		$hash = abs(crc32($line['user']));
		$rgb = array($hash % 255, $hash % 255, $hash % 255);
		$rgb[$hash % 2] = 0;
	?>
 	<tr>
		<td class="time"><?=$line['time'];?></td>
		<td class="user" style="color: rgb(<?=implode(',' , $rgb)?>);"><?=$line['user']?></td>
		<td><?= $h($line['message']); ?></td>
	</tr>
<?php endforeach; ?>
</table>

<div class="paging">
	<?php if ($previous)
		echo $this->html->link('&larr;', array(
			'plugin' => 'li3_bot', 'controller' => 'logs', 'action' => 'view',
			'args' => array($channel, $previous)
		), array('class' => 'prev', 'escape' => false));
	?>
	<?php if ($next)
		echo $this->html->link('&rarr;', array(
			'plugin' => 'li3_bot', 'controller' => 'logs', 'action' => 'view',
			'args' => array($channel, $next)
		), array('class' => 'next', 'escape' => false));
	?>
</div>