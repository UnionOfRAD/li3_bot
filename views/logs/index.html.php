<?php

//
// Copyright (c) 2007 Josh Nathanson
// Stolen from http://www.jnathanson.com/blog/client/jquery/heatcolor/jquery.heatcolor.0.0.1.js
//
$heatColor = function($v, $min, $max) {
	$process = function($n) {
		$lightness = 0.55;

		// adjust lightness
		$n = floor($n + $lightness * (256 - $n));

		// turn to hex
		$n = dechex($n);

		// if no first char, prepend 0
		$n = strlen($n) === 1 ? '0' . $n : $n;

		return $n;
	};

	// value between 1 and 0
	$pos = $v - $min;

	// this adds 0.5 at the top to get red, and limits the bottom at x= 1.7 to get purple
	$shift = 0.5 * $pos + 1.7 * (1 - $pos);

	// scale will be multiplied by the cos(x) + 1
	// (value from 0 to 2) so it comes up to a max of 255
	$scale = 128;

	// period is 2Pi
	$period = 2 * M_PI;

	// x is place along x axis of cosine wave
	$x = $shift + $pos + $period;

	$r = $process(floor((cos($x) + 1) * $scale));
	$g = $process(floor((cos($x + M_PI / 2) + 1) * $scale));
	$b = $process(floor((cos($x + M_PI) + 1) * $scale));

	return '#' . $r . $g . $b;
};

$map = [
	1 => 'January',
	2 => 'February',
	3 => 'March',
	4 => 'April',
	5 => 'May',
	6 => 'June',
	7 => 'July',
	8 => 'August',
	9 => 'September',
	10 => 'October',
	11 => 'November',
	12 => 'December'
];

?>
<article class="logs-index">
	<h1 class="h-alpha"><?= $this->title("#{$channel} Channel Logs for {$year}"); ?></h1>

	<div class="cals">
		<?php foreach ($calendar as $year => $months): ?>
		<div class="cal cal-year">
			<?php foreach ($months as $month => $days): ?>
			<div class="cal-month">
				<span class="name h-gamma"><?= $map[$month] ?></span>

				<?php foreach ($days as $day => $item): ?>
				<?php if ($item['count']): ?>
					<?= $this->html->link($day, array(
						'library' => 'li3_bot',
						'controller' => 'logs', 'action' => 'view'
					) + compact('channel') + ['date' => $item['date']->format('Y-m-d')], [
						'class' => 'cal-day',
						'style' => $item['count'] !== null ? 'background-color: ' . $heatColor(min($item['count'], 200), 0, 200) . ';' : null
					]); ?>
				<?php else: ?>
					<div class="cal-day"><?= $day ?></div>
				<?php endif ?>
				<?php endforeach ?>
			</div>
			<?php endforeach ?>
		</div>
		<?php endforeach ?>
	</div>
	<div class="clearfix"></div>

	<nav class="nav-paging">
		<?php if ($previous)
			echo $this->html->link('&larr; previous', array(
				'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'index',
				'year' => $previous,
			) + compact('channel'), array('rel' => 'prev', 'escape' => false));
		?>
		<?php if ($next)
			echo $this->html->link('next &rarr;', array(
				'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'index',
				'year' => $next,
			) + compact('channel'), array('rel' => 'next', 'escape' => false));
		?>
	</nav>
</article>