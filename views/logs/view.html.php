<p><strong><?=$this->html->link('Channels', 'bot')?> - #<?=$this->html->link($channel, 'bot/'.$channel)?> - <?=$date?></strong></p>

<div class="messages">
<?php foreach ($log as $i => $line): $class = ($i % 2) ? 'even' : 'odd'; ?>
<p class='<?=$class?>'><em><?=$line['time'];?></em> <strong><?=$line['user']?></strong> <?=$line['message']?></p>
<?php endforeach; ?>
</div>
