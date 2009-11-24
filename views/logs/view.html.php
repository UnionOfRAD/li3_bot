<div class="nav">
  <?php if ($previous) echo $this->html->link($previous, 'bot/view/'.$channel.'/'.$previous, array('class' => 'prev')); ?>
  <strong><?=$date?></strong>
  <?php if ($next) echo $this->html->link($next, 'bot/view/'.$channel.'/'.$next, array('class' => 'next')); ?>
</div>

<div class="messages">
<?php foreach ($log as $i => $line): $class = ($i % 2) ? 'even' : 'odd'; ?>
  <p class='<?=$class?>'><em><?=$line['time'];?></em> <strong><?=$line['user']?></strong> <?=$line['message']?></p>
<?php endforeach; ?>
</div>
