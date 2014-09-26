<?php if (empty($logs)): /* Here for BC */ ?>
	<?php $this->title('Channels'); ?>
	<ul class="channels">
		<?php foreach ((array)$channels as $channel): ?>
			 <li><?=$this->html->link("#{$channel}", array(
					'library' => 'li3_bot',
					'controller' => 'logs', 'action' => 'index',
			) + compact('channel')); ?></li>
		<?php endforeach;?>
	</ul>
<?php else: ?>
	<?php $this->title("Logs for channel #{$channel}"); ?>
	<ul>
	  <?php foreach ((array) $logs as $date): ?>
		<li>
			<?php echo $this->html->link($date, array(
				'library' => 'li3_bot',
				'controller' => 'logs', 'action' => 'view'
			) + compact('channel', 'date'));?>
		</li>
	  <?php endforeach;?>
	</ul>
<?php endif; ?>