<?php

namespace lithium_bot\models;

class Tell extends \lithium\core\StaticObject {

	public static $path = null;

	protected static $_tells = array();

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/tmp/tells.txt';
	}

	public static function save($data = array()) {
		static::find('all');
		$file = static::$path;
		$fp = !file_exists($file) ? fopen($file, 'x+') : fopen($file, 'a+');
		$result = false;
		foreach ($data as $key => $value) {
			if (!isset(static::$_tells[$key])) {
				$result = fwrite($fp, "{$key}={$value}\n");
				static::$_tells[$key] = $value;
			}
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

	public static function process($data) {
		static::find('all');
		$key = null;
		extract($data);
		if ($message[0] == '~') {
			$words = preg_split("/[\s]/", $message, 4);
			if ($words[0] == '~tell') {
				if ($words[2] == 'about') {
					$key = $words[3];
					$to = $words[1];
				}
			} else {
				$key = ltrim($words[0], '~');
				$to = $user;
			}
			if (isset(static::$_tells[$key])) {
				$tell = static::$_tells[$key];
				return "{$to}, {$key} is {$tell}";
			}
			return "{$user}, I do not know about {$key}";
		}
		if (stripos($message, $nick) !== false) {
			$words = preg_split("/[\s]/", $message, 4);
			if (!empty($words[2]) && $words[2] == 'is') {
				if (isset(static::$_tells[$words[1]])) {
					$tell = static::$_tells[$words[1]];
					return "{$user}, I thought {$words[1]} was {$tell}";
				} else {
					if (static::save(array($words[1] => $words[3]))) {
						return "{$user}, I will remember {$words[1]}";
					}
				}
			}
			return $words;
		}
	}

	public static function reset() {
		static::$_tells = array();
	}
}

?>