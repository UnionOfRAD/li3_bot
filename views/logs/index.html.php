<ul>
	<?php
		if (!empty($channels)){
			foreach ($channels as $channel) {?>
				<li><a href="/bot/<?php echo $channel; ?>"><?php echo $channel; ?></a></li>
	<?php 	}
		}?>

	<?php
		if (!empty($logs)){
			foreach ($logs as $date) {
			 if (!preg_match("/(.*?)-(.*?)-(.*?)/", $date)) continue; ?>
				<li>
					<a href="/bot/view/<?php echo $channel; ?>/<?php echo $date; ?>">
						<?php echo $date; ?>
					</a>
				</li>
	<?php 	}
		}?>
</ul>
