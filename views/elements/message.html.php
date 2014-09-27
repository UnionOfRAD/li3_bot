<?php

	$nickRgb = function($nick) {
		$hash = abs(crc32($nick));

		$rgb = array($hash % 255, $hash % 255, $hash % 255);
		$rgb[$hash % 2] = 0;

		return $rgb;
	};

	$message = preg_replace_callback(
		'@(https?://([-\w\.]+)+(:\d+)?(/([-\w/_\.,#\(\)={}\+\?]*(\?\S+)?)?)?)@',
		function($matches) use ($rewriters) {
			$outer = $matches[0];

			foreach ($rewriters as $pattern => $rewriter) {
				if (preg_match('#' . $pattern. '#', $outer, $m)) {
					return $rewriter($m[0], $outer);
				}
			}
			return '<a href="' . $inner . '" rel="nofollow">' . $outer . '</a>';
		},
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
			'date' => $item->created()->format('Y-m-d'),
			'channel' => ltrim($channel, '#')
		), array('title' => 'context')); ?>
	</td>
<?php else: ?>
<tr>
	<td class="time"><?=$item['time'] ?></td>
<?php endif; ?>
	<td class="user" style="color: rgb(<?=implode(',' , $nickRgb($item->user))?>);"><?=$item->user ?></td>
	<td class="message"><?php echo $message; ?></td>
</tr>