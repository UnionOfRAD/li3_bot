<?php

namespace li3_bot\models;

use \DirectoryIterator;

class Log extends \lithium\core\StaticObject {

	public static $path = null;
	protected static $_pattern = null;

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/tmp/logs/';
		static::$_pattern = '/^(?P<time>\d+:\d+(:\d+)?) : (?P<user>[^\s]+) : (?P<message>.*)/';
	}

	public static function save($data = null) {
		$dir = static::path($data['channel']);
		$path = static::path($data['channel'], date('Y-m-d'));
		if (!is_dir($dir)) {
			mkdir($dir);
		}

		$fp = !file_exists($path) ? fopen($path, 'x+') : fopen($file, 'a+');
		if (!is_resource($fp)) {
			return false;
		}

		$log = date('H:i:s') . " : {$data['user']} : {$data['message']}\n";
		fwrite($fp, $log);
		fclose($fp);
		return $data;
	}

	public static function read($channel, $date) {
		$path = static::path($channel, $date);

		if (!static::exists($channel, $date)) {
			return array();
		}

		$fp = fopen($path, 'r+');
		$log = array();

		while (!feof($fp)) {
			$line = fgets($fp);

			if (preg_match(static::$_pattern, $line, $matches)) {
				$log[] = $matches;
			}
		}

		fclose($fp);
		return $log;
	}

	public static function find($type, $options = array()) {
		if (!empty($options['channel'])) {
			$path = static::path($options['channel']);
			if (!is_dir($path)) {
				return array();
			}

			return array_values(array_filter(scandir($path), function ($file) {
				if ($file[0] == '.') {
					return false;
				}
				return true;
			}));
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

	public static function exists($channel, $date = null) {
		$path = static::path($channel, $date);

		return is_dir($path) || is_file($path);
	}

	public static function path($channel, $date = null) {
		$path = static::$path.'#'.$channel;

		if (!is_null($date)) {
			$path .= '/'.$date;
		}

		return $path;
	}
}

?>
