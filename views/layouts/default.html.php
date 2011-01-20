<!doctype html>
<html>
<head>
	<?php echo $this->html->charset(); ?>
	<title>Lithium Bot <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('lithium', '/li3_bot/css/li3_bot')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="bot">
	<div id="container">
		<div id="header">
			<h1>Lithium</h1>
			<h2>Bot</h2>
			<?php if (isset($breadcrumbs)): ?>
				<ul class="crumbs">
				<?php foreach ($breadcrumbs as $crumb): ?>
					  <li><?php echo $crumb['url'] ? $this->html->link($crumb['title'], $crumb['url']) : $crumb['title']; ?></li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<div id="content">
			<?php echo $this->content; ?>
		</div>
	</div>
</body>
</html>