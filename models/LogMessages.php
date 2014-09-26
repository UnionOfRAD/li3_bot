<?php

namespace li3_bot\models;

use DateTime;
use DateInterval;
use lithium\storage\Cache;
use lithium\core\Libraries;

class LogMessages extends \lithium\data\Model {

	public static function channels() {
		return array_map(function($v) {
			return ltrim($v, '#');
		}, Libraries::get('li3_bot', 'channels'));
	}

	public static function calendar($channel, $year = null) {
		$cacheKey = 'li3_bot_log_messages_calendar_' . md5($channel . $year);

		if ($cached = Cache::read('default', $cacheKey)) {
			return $cached;
		}
		$query = [
			'fields' => [
				"DISTINCT(DATE(created)) as created",
			],
			'conditions' => [
				'channel' => $channel
			]
		];
		if ($year) {
			$query['conditions'][] = ["CAST(YEAR(created) as integer)" => $year];
		}
		$results = static::find('all', $query);
		$data = [];

		$years = [];
		foreach ($results->data() as $result) {
			$date = DateTime::createFromFormat('Y-m-d', $result['created']);
			$years[] = $date->format('Y');
		}
		foreach (array_unique($years) as $year) {
			// Go through each day of the year and add it..
			$date = new DateTime("$year-01-01");
			$interval = new DateInterval('P1D');

			while ($date->format('Y') == $year) {
				list($y, $m, $d) = explode('-', $date->format('Y-n-j'));

				$data[$y][$m][$d] = [
					'count' => null,
					'date' => $date
				];
				$date->add($interval);
			}
		}

		$start = time();
		$calcCount = true;
		foreach ($results->data() as $result) {
			if ($calcCount && (time() - $start) > 15) {
				$calcCount = false;
			}
			$date = DateTime::createFromFormat('Y-m-d', $result['created']);

			$data[$date->format('Y')][$date->format('n')][$date->format('j')] = [
				'count' => $calcCount ? static::totalMessages($channel, $date->format('Y-m-d')) : 20,
				'date' => $date
			];
		}
		if ($calcCount) {
			Cache::write('default', $cacheKey, $data, '+12 hours');
		}
		return $data;
	}

	public static function totalMessages($channel, $date) {
		$cacheKey = 'li3_bot_log_messages_total_messages_' . md5($channel .  $date);

		if (($cached = Cache::read('default', $cacheKey)) !== null) {
			return $cached;
		}
		$result = static::find('count', [
			'conditions' => [
				'channel' => $channel,
				"DATE(created)" => $date
			]
		]);
		if (date('Y-m-d') != $date) { // Do not cache ongoing days.
			Cache::write('default', $cacheKey, $result, Cache::PERSIST);
		}
		return $result;
	}

	public static function day($channel, $date) {
		$cacheKey = 'li3_bot_log_messages_day_' . md5($channel .  $date);

		if ($cached = Cache::read('default', $cacheKey)) {
			return $cached;
		}
		$result = static::find('all', [
			'conditions' => [
				'channel' => $channel,
				"DATE(created)" => $date
			]
		]);
		if (date('Y-m-d') != $date) { // Do not cache ongoing days.
			Cache::write('default', $cacheKey, $result, Cache::PERSIST);
		}
		return $result;
	}

	public static function hasDay($channel, $date) {
		$cacheKey = 'li3_bot_log_messages_has_day_' . md5($channel .  $date);

		if (($cached = Cache::read('default', $cacheKey)) !== null) {
			return $cached;
		}
		$result = (boolean) static::find('count', [
			'conditions' => [
				'channel' => $channel,
				"DATE(created)" => $date
			]
		]);
		if (date('Y-m-d') != $date) { // Do not cache ongoing days.
			Cache::write('default', $cacheKey, $result, Cache::PERSIST);
		}
		return $result;
	}

	public static function hasYear($channel, $date) {
		$cacheKey = 'li3_bot_log_messages_has_year_' . md5($channel .  $date);

		if (($cached = Cache::read('default', $cacheKey)) !== null) {
			return $cached;
		}
		$result = (boolean) static::find('count', [
			'conditions' => [
				'channel' => $channel,
				"YEAR(created)" => $date
			]
		]);
		if ($date <= date('Y')) { // Do not cache future missed years.
			Cache::write('default', $cacheKey, $result, Cache::PERSIST);
		}
		return $result;
	}

	public function created($entity) {
		return DateTime::createFromFormat('Y-m-d H:i:s', $entity->created);
	}
}

?>