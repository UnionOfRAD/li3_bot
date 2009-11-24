<!doctype html>
<html>
  <head>
    <title><?php echo $this->title(); ?></title>
    <?php echo $this->scripts(); ?>
	<style type='text/css'>
    a { color: blue; text-decoration: none; }
	a:hover { text-decoration: underline; }
	.breadcrumb { list-style: none; background: #e9e9e9; margin: .5em 0; padding: .25em; border: 1px solid #999; }
	.breadcrumb li { display: inline; }
	.breadcrumb li + li:before { content: ' > '; }
	.channels { float: left; list-style: none; background: #e9e9e9; width: 100px; margin: 0; padding: 0; border: 1px solid #999; }
    .channels a { display: block; margin-top: -1px; padding: .25em; border-top: 1px solid #999; }
    .channels a:hover { background: #e0e0e0; }
	.nav { margin-bottom: .5em; text-align: center; height: 1em; }
    .nav a.prev { float: left; }
    .nav a.next { float: right; margin-top: 0; }
    .content { margin-left: 120px; }
    .messages p { border: 1px solid #999; margin: -1px 0 0; padding: 2px; font-family: monospace; }
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

	<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $link => $title): ?>
      <li><?php echo ($link != '#') ? $this->html->link($title, $link) : $title; ?></li>
<?php endforeach; ?>
	</ul>

    <ul class="channels">
<?php foreach ((array)$channels as $channel): ?>
	  <li><?=$this->html->link('#'.$channel, 'bot/'.$channel); ?></li>
<?php endforeach;?>
    </ul>

    <div class="content">
<?=$this->content(); ?>
    </div>
  </body>
</html>
