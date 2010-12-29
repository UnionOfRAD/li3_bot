<?php $this->title("Logs for {$channel} on {$date}"); ?>
<table class="messages">
<?php foreach ($log as $i => $line): ?>
	<?php
		$hash = abs(crc32($line['user']));
		$rgb = array($hash % 255, $hash % 255, $hash % 255);
		$rgb[$hash % 2] = 0;
		$message = preg_replace(
			'@(https?://([-\w\.]+)+(:\d+)?(/([-\w/_\.,#\(\)={}\+\?]*(\?\S+)?)?)?)@',
			'<a href="$1">$1</a>',
			$h($line['message'])
		);
	?>
	<tr id="<?php echo $i ?>">
		<td class="time">
			<?=$this->html->link($line['time'], array(
				'library' => 'li3_bot',
				'controller' => 'logs', 'action' => 'view',
				'#' => $i
			) + compact('channel', 'date')); ?>
		</td>
		<td class="user" style="color: rgb(<?=implode(',' , $rgb)?>);"><?=$line['user']?></td>
		<td class="message"><?php echo $message; ?></td>
	</tr>
<?php endforeach; ?>
</table>

<div class="paging">
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
</div>