<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\models;

use DirectoryIterator;
use Exception;

class Log extends \lithium\core\StaticObject {

	public static $path = null;

	protected static $_pattern = null;

	public static function __init() {
		static::$path = $path = LITHIUM_APP_PATH . '/resources/bot/logs';
		static::$_pattern = '/^(?P<time>\d+:\d+(:\d+)?) : (?P<user>[^\s]+) : (?P<message>.*)/';

		if (!is_dir($path)) {
			throw new Exception("Logs directory at `{$path}` doesn't exist");
		}
		if (!is_readable($path)) {
			throw new Exception("Logs directory at `{$path}` is not readable");
		}
		if (!is_writable($path)) {
			throw new Exception("Logs directory at `{$path}` is not writable");
		}
	}

	public static function save($data = null) {
		$dir = static::path($data['channel']);
		$path = static::path($data['channel'], date('Y-m-d'));

		if (!is_dir($dir)) {
			mkdir($dir);
		}

		$colorCodes = '\x2\x3\x36\x311\x313';
		$data['message'] = preg_replace("/[{$colorCodes}]/", null, $data['message']);

		$line = date('H:i:s') . " : {$data['user']} : {$data['message']}\n";
		file_put_contents($path, $line, FILE_APPEND);

		return $data;
	}

	public static function read($channel, $date) {
		$path = static::path($channel, $date);

		if (!static::exists($channel, $date)) {
			return array();
		}
		$log = array();
		$fp = @fopen($path, 'r');

		if (!is_resource($fp)) {
			return $log;
		}

		while ($line = fgets($fp)) {
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
				return $file[0] != '.';
			}));
		}

		$directory = new DirectoryIterator(static::$path);
		$results = array();
		foreach ($directory as $dir) {
			$name = $dir->getFilename();

			if ($dir->isDot() || !$dir->isDir()) {
				continue;
			}
			$results[] = $name;
		}
		return $results;
	}

	public static function search($regex, array $options = array()) {
		$default = array('channel' => null);
		$options += $default;

		$path = static::path($options['channel']);

		$dates = array_values(array_filter(scandir($path), function ($file) {
			return $file[0] != '.';
		}));
		$results = array();

		foreach ($dates as $i => $date) {
			$data = static::read($options['channel'], $date);

			foreach ($data as $item) {
				$match  = preg_match("#{$regex}#", $item['user']);
				$match |= preg_match("#{$regex}#", $item['message'], $matches);

				if (!$match) {
					continue;
				}
				$results[] = $item + compact('date', 'matches');
			}
		}
		return $results;
	}

	public static function exists($channel, $date = null) {
		$path = static::path($channel, $date);

		return is_dir($path) || is_file($path);
	}

	public static function path($channel, $date = null) {
		$path = static::$path . '/'. str_replace('#', '', $channel);

		if (!is_null($date)) {
			$path .= '/' . $date;
		}
		return $path;
	}
}

?>