<h2><?php echo $this->title('Tells'); ?></h2>
<dl>
<?php foreach ($tells as $key => $value): ?>
	<dt><?php echo $key ?></dt><dd><?php echo $value ?></dd>
<?php endforeach; ?>
</dl>