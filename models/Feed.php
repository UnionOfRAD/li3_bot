<?php

namespace li3_bot\models;

use \lithium\util\String;
use \lithium\util\Set;

class Feed extends \lithium\core\StaticObject {

	public static $path = null;

	public static $format;

	protected static $_data = array();

	protected static $_dates = array();

	protected static $_queue = array();

	protected static $_firstPing = true;

	protected static $_config = array();

	public static function __init() {
		$plugin = dirname(__DIR__);
		static::$path = $plugin . '/config/li3_bot.ini';
		static::$format = join(' ', array(
			"\x02{:name}\x02", "\x0311∆\x03", "{:description}", "\x036∆\x03",
			"\x02{:author}\x02", "\x0313∆\x03", "{:link}"
		));
	}

	public static function config() {
		if (empty(static::$_config)) {
			static::$_config = parse_ini_file(static::$path, true);
		}
		return static::$_config;
	}

	public static function find($type = 'first', $options = array()) {
		$defaults = array('ping' => true, 'name' => null, 'path' => null);
		$options += $defaults;

		if ($options['ping'] && static::$_firstPing) {
			static::$_firstPing = false;
			return array();
		}

		if (empty($options['name'])) {
			return array();
		}

		if (empty($options['path']) && !empty(static::$_config['feeds'][$options['name']])) {
			$options['path'] = static::$_config['feeds'][$options['name']];
		}

		if (empty($options['path'])) {
			return array();
		}

		$name = $options['name'];
		$data = static::read($options['path']);

		if (empty($data['channel']['item'])) {
			return array();
		}
		foreach ($data['channel']['item'] as &$item) {
			$item['pubDate'] = strtotime($item['pubDate']);
		}
		static::$_data[$name] = $data;
		$items = Set::extract('/channel/item', $data);

		if (empty(static::$_dates[$name])) {
			static::_date($name);
			static::$_dates[$name] = static::$_dates[$name] - 1; // uncomment to test first ping
		}

		if ($type === 'new') {
			$items = Set::extract(
				'/channel/item[pubDate>' . static::$_dates[$name] . ']', $data
			);
			static::_date($name);
		}

		# if triggered by user, tell them there is nothing to say
		if (!count($items) && !$options['ping']) {
			return array('get back to work');
		}

		if (empty($items)) {
			return array();
		}

		$result = array();
		$replace = array("#", "\r\n", "\n");
		$ments = array("", ": ", ": ");
		foreach ($items as $item) {
			$description = str_replace($replace, $ments, strip_tags($item['item']['description']));
			if (strlen($description) > 50) {
				$description = substr($description, 0, 50);
			}
			$result[] = String::insert(static::$format, array(
				'name' => $name,
				'author' => $item['item']['author'],
				'description' => $description,
				'link' => $item['item']['link'],
			));

			if (count($result) > 3) {
				break;
			}
		}
		return $result;
	}

	/**
	* @todo datasource
	*/
	public static function read($url) {
		$xml = @simplexml_load_file($url);
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