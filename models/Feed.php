<?php

namespace li3_bot\models;

//use \lithium\data\Http;
use \lithium\util\Set;

class Feed extends \lithium\core\StaticObject {

	public static $path = null;

	protected static $_data = array();

	protected static $_dates = array();

	protected static $_queue = array();

	protected static $_firstPing = true;

	protected static $_config = array();

	public static function __init() {
		$plugin = dirname(__DIR__);
		static::$path = $plugin . '/config/li3_bot.ini';
	}

	public static function config($config = array()) {
		if (empty(static::$_config)) {
			if (empty($config)) {
				$config = parse_ini_file(static::$path, true);
			}
			static::$_config = $config;
		}
		return static::$_config;
	}

	public static function poll() {
		$responses = array();
		$config = static::config();
		foreach ($config['feeds'] as $name => $path) {
			$options = compact('name', 'path');
			$responses = array_merge($responses, static::find('new', $options));
		}
		return $responses;
	}

	public static function find($type = 'first', $options = array()) {
		$defaults = array('ping' => true, 'name' => null, 'path' => null);
		$options += $defaults;

		# bot seems to get flooded if we send messages on first ping/pong
		if ($options['ping'] && static::$_firstPing) {
			static::$_firstPing = false;
			return array();
		}

		if (!$options['name'] || !$options['path']) {
			return array();
		}

		$name = $options['name'];
		$data = static::read($options['path']);
		foreach ($data['channel']['item'] as &$item) {
			$item['pubDate'] = strtotime($item['pubDate']);
		}
		static::$_data[$name] = $data;

		# set date pointer to most recent
		if (empty(static::$_dates[$name])) {
			static::_date($name);
		}

		# find type to show new entries since last check
		if ($type === 'new') {
			$items = Set::extract(
				'/channel/item[pubDate>' . static::$_dates[$name] . ']',
				static::$_data[$name]
			);
			static::_date($name);
		}

		# if triggered by user, tell them there is nothing to say
		if (!count($items) && !$options['ping']) {
			return array('get back to work');
		}

		if (empty(static::$_data[$name]['channel']['item'])) {
			return array();
		}
		$items = array();
		foreach (static::$_data[$name]['channel']['item'] as $item) {
			$description = strip_tags($item['description']);
			if (strlen($description) > 50) {
				preg_match("/[a-zA-Z0-9]{0, 50}/", $description, $description);
			}
			$items[] = $item['author'] . " > " . $description . "... > " . $item['link'];
		}
		return $items;
	}

	/**
	* @todo datasource
	*/
	public static function read($url) {
		$xml = simplexml_load_file($url);
		$xml = Set::reverse($xml);
		return $xml;
	}

	public static function reset() {
		static::$_data = array();
		static::$_dates = array();
		static::$_queue = array();
		static::$_firstPing = true;
		static::$_config = array();
	}

	protected static function _date($name) {
		$date = Set::extract('/channel/item[1]/pubDate', static::$_data[$name]);
		static::$_dates[$name] = array_shift($date);
		return static::$_dates[$name];
	}
}
?>