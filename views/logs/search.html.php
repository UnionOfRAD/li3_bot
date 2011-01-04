<?php $this->title("Search results for {$channel} logs"); ?>

<?=$this->form->create(null, array(
	'url' => "/bot/logs/search/{$channel}",
	'class' => 'search',
	'method' => 'get'
)) ?>
<?=$this->form->field('query', array(
	'type' => 'search',
	'placeholder' => 'regex',
	'label' => 'Search',
	'value' => $query
)); ?>
<?=$this->form->end(); ?>

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
	$previous = $i == 0 ? null : $log[$i--];
?>

<?php if (!$previous || $line['date'] != $previous['date']): ?>
	</table>
	<h3><?php echo $line['date'] ?></h3>
	<table class="messages">
<? endif ?>

<tr id="<?php echo $i ?>">
	<td class="time">
		<?=$this->html->link($line['time'], array(
			'library' => 'li3_bot',
			'controller' => 'logs', 'action' => 'view',
			'#' => $i,
			'date' => $line['date']
		) + compact('channel'), array('title' => 'context')); ?>
	</td>
	<td class="user" style="color: rgb(<?=implode(',' , $rgb)?>);"><?=$line['user']?></td>
	<td class="message"><?php echo $message; ?></td>
</tr>
<?php endforeach; ?>
</table>
