<?php if (empty($logs)): ?>
<p>Choose a channel to the left.</p>
<?php else: ?>
<ul>
  <?php foreach ((array)$logs as $date): ?>
    <li>
      <a href="/bot/view/<?=$channel; ?>/<?=$date; ?>">
        <?=$date; ?>
      </a>
    </li>
  <?php endforeach;?>
</ul>
<?php endif; ?>
