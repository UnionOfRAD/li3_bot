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
