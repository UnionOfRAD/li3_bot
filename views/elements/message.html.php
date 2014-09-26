<?php
	$nickRgb = function($nick) {
		$hash = abs(crc32($nick));

		$rgb = array($hash % 255, $hash % 255, $hash % 255);
		$rgb[$hash % 2] = 0;

		return $rgb;
	};

	$message = preg_replace(
		'@(https?://([-\w\.]+)+(:\d+)?(/([-\w/_\.,#\(\)={}\+\?]*(\?\S+)?)?)?)@',
		'<a href="$1" rel="nofollow">$1</a>',
		$h($item->message)
	);
?>
<?php if ($id): ?>
<tr id="<?php echo $id ?>">
	<td class="time">
		<?=$this->html->link($item->created()->format('H:i:s'), array(
			'library' => 'li3_bot',
			'controller' => 'logs', 'action' => 'view',
			'#' => $id,
			'date' => $item->created()->format('Y-m-d')
		) + compact('channel'), array('title' => 'context')); ?>
	</td>
<?php else: ?>
<tr>
	<td class="time"><?=$item['time'] ?></td>
<?php endif; ?>
	<td class="user" style="color: rgb(<?=implode(',' , $nickRgb($item->user))?>);"><?=$item->user ?></td>
	<td class="message"><?php echo $message; ?></td>
</tr>