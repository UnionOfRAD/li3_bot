<article class="tells-index">
	<h1 class="h-alpha"><?php echo $this->title('Tells'); ?></h2>
	<table class="tells">
	<?php foreach ($tells as $item): ?>
		<tr>
			<td><?= $item->key ?>
			<td><?= $item->value ?>
	<?php endforeach; ?>
	</table>
</article>