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
	$previous = $i == 0 ? null : $log[$i - 1];
?>
<?php if (!$previous || $line['date'] != $previous['date']): ?>
	</table>
	<h3><?php echo $line['date'] ?></h3>
	<table class="messages">
<?php endif ?>

<?=$this->view()->render(
	array('element' => 'log_row'),
	array('id' => $i, 'item' => $line) + compact('channel'),
	array('library' => 'li3_bot')
); ?>
<?php endforeach; ?>
</table>