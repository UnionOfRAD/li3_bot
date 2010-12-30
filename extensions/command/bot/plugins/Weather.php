<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright  Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license	   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot\plugins;

use \lithium\util\String;

/**
 * Weather plugin
 *
 */
class Weather extends \li3_bot\extensions\command\bot\Plugin {

	protected $_classes = array(
		'model' => '\li3_bot\models\Weather',
		'response' => '\lithium\console\Response'
	);

	/**
	 * possible responses
	 *
	 * @var array
	 */
	protected $_responses = array(
		'missing' => 'I need a location, {:user}.',
		'unknown' => '{:user}, I cannot find {:location}.',
		'weather' => 'The weather in {:city}, {:state}, {:country} is {:temperature} with wind {:wind} (feels like {:windchill}).',
	);
	/**
	 * Process incoming messages
	 *
	 * @param string $data
	 * @return string
	 */
	public function process($data) {
		$responses = $this->_responses;
		$model = $this->_classes['model'];
		$location = null;
		extract($data);

		$words = preg_split("/[\s]/", $message, 2);

		if ($words[0] != '~weather') {
			return;
		}

		if (!isset($words[1])) {
			return String::insert($responses['missing'], compact('user'));
		}

		$location = $model::find('search', $words[1]);

		if (!$location || isset($location->title)) {
			return String::insert($responses['unknown'], compact('user') + array('location' => $words[1]));
		}

		if (isset($location->location)) {
			$location = $model::find('search', $location->location[0]->name);

			if (!$location || isset($location->title)) {
				return String::insert($responses['unknown'], compact('user') + array('location' => $words[1]));
			}
		}

		$station = $location->nearby_weather_stations->airport->station[0];
		$weather = $model::find('station', ((string)$station->icao));

		if (!$weather || isset($weather->title)) {
			return String::insert($responses['unknown'], compact('user') + array('location', $words[1]));
		}

		return String::insert($responses['weather'], compact('user') + array(
			'city' => (string)$station->city,
			'state' => (string)$station->state,
			'country' => (string)$station->country,
			'icao' => (string)$station->icao,
			'temperature' => (string)$weather->temperature_string,
			'windchill' => (string)$weather->windchill_string,
			'wind' => (string)$weather->wind_string
		));
	}
}

?>