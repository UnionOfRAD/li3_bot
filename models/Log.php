<?php

namespace lithium_bot\models;

class Log extends \lithium\core\StaticObject {
	
	public static $path = null;

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/tmp/logs/';
	}

	public static function save($data = null) {
		$file = static::$path . date('Y-m-d');
		$fp = !file_exists($file) ? fopen($file, 'x+') : fopen($file, 'a+');
		return fwrite($fp, $data);
	}

	public static function read($date) {
		return file_get_contents(static::$path . $date);
	}
	
	public static function all() {
		return scandir(static::$path);
	}
}

?>