<nav class="crumbs">
	<ul>
		<li
			itemscope itemtype="http://data-vocabulary.org/Breadcrumb"
			><?= $this->html->link('Bot', 'li3_bot.Pages::home', ['itemprop' => 'title url']) ?>
		<?php foreach ($data as $crumb): ?>
			<li
				itemscope itemtype="http://data-vocabulary.org/Breadcrumb"
			>
			<?php if ($crumb['url']): ?>
				<?= $this->html->link($crumb['title'], $crumb['url'], ['itemprop' => 'title url']) ?>
			<?php else: ?>
				<span itemprop="title"><?= $crumb['title']; ?></span>
			<?php endif ?>
		<?php endforeach; ?>
	</ul>
</nav>