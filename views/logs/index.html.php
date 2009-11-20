<?php if (!empty($channel)): ?>
<p><?=$this->html->link('Channels', 'bot')?> - #<?=$channel?></p>
<?php else: ?>
<p>Channels</p>
<?php endif; ?>

<ul>
	<?php foreach ((array)$channels as $channel): ?>
		<li><a href="/bot/<?=$channel; ?>"><?=$channel; ?></a></li>
	<?php endforeach;?>

	<?php foreach ((array)$logs as $date): ?>
		<li>
			<a href="/bot/view/<?=$channel; ?>/<?=$date; ?>">
				<?=$date; ?>
			</a>
		</li>
	<?php endforeach;?>
</ul>
