<article class="tells-index">
	<h1><?php echo $this->title('Tells'); ?></h2>
	<dl class="tells">
	<?php foreach ($tells as $key => $value): ?>
		<dt><?php echo $key ?></dt><dd><?php echo $value ?></dd>
	<?php endforeach; ?>
	</dl>
</article>