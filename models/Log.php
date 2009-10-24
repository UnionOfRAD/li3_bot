<?php

namespace li3_bot\models;

use \DirectoryIterator;

class Log extends \lithium\core\StaticObject {

	public static $path = null;

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/tmp/logs/';
	}

	public static function save($data = null) {
		$file = static::$path . $data['channel'] . '/' . date('Y-m-d');
		if (!is_dir(static::$path . $data['channel'])) {
			mkdir(static::$path . $data['channel']);
		}
		$fp = !file_exists($file) ? fopen($file, 'x+') : fopen($file, 'a+');
		if (!is_resource($fp)) {
			return false;
		}
		$log = date('H:i:s') . " : {$data['user']} : {$data['message']}\n";
		fwrite($fp, $log);
		fclose($fp);
		return $data;
	}

	public static function read($date) {
		return file_get_contents(static::$path . $date);
	}

	public static function find($type, $options = array()) {

		if (!empty($options['channel'])) {
			return scandir(static::$path . '#' . $options['channel']);
		}

		$directory = new DirectoryIterator(static::$path);
		$results = array();
		foreach ($directory as $dir) {
			$name = $dir->getFilename();
			if (strpos($name, '#') === false) {
				continue;
			}
			$results[] = substr($name, 1);
		}
		return $results;
	}
}

?>