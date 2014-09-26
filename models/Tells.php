<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\models;

use lithium\core\Libraries;

class Tells extends \lithium\core\StaticObject {

	public static $path = null;

	protected static $_tells = array();

	public static function init() {
		static::$path = Libraries::get(true, 'resources') . '/bot/tells.ini';
	}

	public static function save($data = array()) {
		static::find('all');
		$file = static::$path;
		$fp = !file_exists($file) ? fopen($file, 'x+') : fopen($file, 'a+');
		$result = false;
		$data = array_diff($data, static::$_tells);
		foreach ($data as $key => $value) {
			$result = fwrite($fp, "{$key}=\"{$value}\"\n");
			static::$_tells[$key] = $value;
		}
		fclose($fp);
		if ($result) {
			return true;
		}
		return false;
	}

	public static function find($type = 'first') {
		if (empty(static::$_tells)) {
			if (!file_exists(static::$path)) {
				return array();
			}
			static::$_tells = parse_ini_file(static::$path);
		}

		if ($type === 'all') {
			return static::$_tells;
		}

		if ($type === 'first') {
			return current(static::$_tells);
		}

		if (isset(static::$_tells[$type])) {
			return static::$_tells[$type];
		}
	}

	public static function recent() {
		return array_slice(array_reverse(static::find('all'), true), 0, 10, true);
	}

	public static function delete($key) {
		if (isset(static::$_tells[$key])) {
			$tells = static::$_tells;
			unset($tells[$key]);
			static::reset();
			unlink(static::$path);
			static::save($tells);
			return true;
		}
	}

	public static function reset() {
		static::$_tells = array();
	}

	public static function toIni($data) {
		$result = array();
		foreach ($data as $key => $value) {
			$result[] = "{$key}={$value}";
		}
		return join("\n", $result);
	}
}

Tells::init();

?>