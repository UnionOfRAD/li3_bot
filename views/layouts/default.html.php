<html>
<head>
	<title><?php echo $this->title(); ?></title>
	<?php echo $this->scripts(); ?>
	<style type='text/css'>
	.messages p {
		border: 1px solid #999;
		margin: -1px 0 0;
		padding: 2px;
		font-family: monospace;
	}
	.messages p.odd { background-color: #f6f6f6; }
	.messages p.even { background-color: #f0f0f0; }
	.messages p:hover { background: #e6e6e6; }
	.messages p em { font-style: normal; }
	.messages p strong:before { content: '<'; }
	.messages p strong:after { content: '>'; }
	</style>
</head>
<body>
	<h1>Lithium Bot</h1>
	<?php echo $this->content(); ?>
</body>
</html>
