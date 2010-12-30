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
		return 0;
	}

	public static function highscore() {
		$data = static::_readIni();

		arsort($data, SORT_NUMERIC);
		return array_slice($data, 0, 10, true);
	}

	public static function increment($user) {
		$data = static::_readIni();

		isset($data[$user]) ? $data[$user]++ : $data[$user] = 1;

		return static::_writeIni($data);
	}

	public static function decrement($user) {
		$data = static::_readIni();

		isset($data[$user]) ? $data[$user]-- : $data[$user] = 0;

		return static::_writeIni($data);
	}

	protected static function _readIni() {
		if (!file_exists(static::$path)) {
			return array();
		}
		return parse_ini_file(static::$path);
	}

	protected static function _writeIni($data) {
		$lines = array("[karmas]");

		foreach ($data as $key => $value) {
			$lines[] = "{$key}={$value}";
		}
		return file_put_contents(static::$path, implode("\n", $lines));
	}
}

?>