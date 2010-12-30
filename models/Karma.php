<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\models;

class Karma extends \lithium\core\StaticObject {

	public static $path = null;

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/resources/bot/karmas.ini';
	}

	public static function current($user) {
		$data = static::_readIni();

		if (isset($data[$user])) {
			return $data[$user];
		}
	}

	public static function increment($user) {
		$data = static::_readIni();
		$data[$user]++;

		return static::_writeIni($data);
	}

	public static function decrement($user) {
		$data = static::_readIni();
		$data[$user]--;

		return static::_writeIni($data);
	}

	protected static function _readIni() {
		if (!file_exists(static::$path)) {
			return array();
		}
		return parse_ini_file(static::$path);
	}

	protected static function _writeIni($data) {
		$lines = array();

		foreach ($data as $key => $value) {
			$lines[] = "{$key}={$value}";
		}
		return file_put_contents(static::$path, $lines);
	}
}

?>