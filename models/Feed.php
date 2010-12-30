<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\models;

use lithium\util\String;
use lithium\util\Collection;

class Feed extends \lithium\core\StaticObject {

	public static $path = null;

	public static $format;

	protected static $_data = array();

	protected static $_dates = array();

	protected static $_firstPing = true;

	protected static $_config = array();

	public static function __init() {
		$plugin = dirname(__DIR__);
		static::$path = $plugin . '/config/li3_bot.ini';
		static::$format = join(' ', array(
			"\x02{:name}\x02", "\x0311∆\x03", "{:title}",
			"\x0311∆\x03", "{:description}", "\x036∆\x03",
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
		$defaults = array(
			'ping' => true,
			'name' => !empty(static::$_config['feeds'][$type]) ? $type : null,
			'path' => null,
			'limit' => ($type === 'new' ? 1 : 4)
		);
		$options += $defaults;

		if (empty($options['name'])) {
			return array();
		}
		if (!empty(static::$_config['feeds'][$type])) {
			$options['name'] = $type;
		}
		if (empty($options['path']) && !empty(static::$_config['feeds'][$options['name']])) {
			$options['path'] = static::$_config['feeds'][$options['name']];
		}
		if (empty($options['path'])) {
			return array();
		}

		$name = $options['name'];

		if ($options['ping'] && static::$_firstPing) {
			static::$_firstPing = false;
			static::$_dates[$name] = 1;
			return array();
		}

		if (empty(static::$_dates[$name])) {
			static::$_dates[$name] = 1;
			return array();
		}

		$data = static::read($options['path']);

		if (empty($data['channel']['item'])) {
			return array();
		}
		$items = array();

		foreach ($data['channel']['item'] as $item) {
			$item = Collection::toArray($item);

			$item['pubDate'] = strtotime($item['pubDate']);
			if ($item['pubDate'] <= static::$_dates[$name] || count($items) >= $options['limit']) {
				break;
			}
			$items[] = $item;
		}

		# if triggered by user, tell them there is nothing to say
		if (!count($items) && !$options['ping']) {
			return array('get back to work');
		}
		if (empty($items)) {
			return array();
		}

		static::$_dates[$name] = $items[0]['pubDate'];

		$result = array();
		$replace = array("#", "\r\n", "\n");
		$ments = array("", ": ", ": ");

		foreach (array_reverse($items) as $item) {
			if (strlen($item['title']) > 30) {
				$item['title'] = substr($item['title'], 0, 20) . '…';
			}
			$description = null;

			if (!empty($item['description'])) {
				$description = str_replace($replace, $ments, strip_tags($item['description']));
				if (strlen($description) > 50) {
					$description = substr($description, 0, 50) . '…';
				}
			}
			$result[] = String::insert(static::$format, array(
				'name' => $name,
				'author' => $item['author'],
				'title' => $item['title'],
				'description' => $description,
				'link' => $item['link'],
			));
		}
		return $result;
	}

	/**
	* @todo datasource
	*/
	public static function read($url) {
		$xml = @simplexml_load_file($url);
		return Collection::toArray($xml);
	}

	public static function reset() {
		static::$_dates = array();
		static::$_firstPing = true;
	}
}

?>