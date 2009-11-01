<?php

namespace li3_bot\models;

//use \lithium\data\Http;
use \lithium\util\Set;

class Feed extends \lithium\core\StaticObject {

	protected static $_feed = array();

	protected static $_date = array();

	protected static $_queue = array();

	protected static $_firstPing = true;

	public static function poll($ping = false) {
	    return static::find('new', $ping);
	}

	public static function find($type = 'first', $ping = false) {

		# bot seems to get flooded if we send messages on first ping/pong
		if ($ping && static::$_firstPing) {
			static::$_firstPing = false;
			return array();
		}

		# get feed, fix dates, store it
		$feed = static::read('http://rad-dev.org/lithium/timeline.rss');
		foreach ($feed['channel']['item'] as &$item) {
			$item['pubDate'] = strtotime($item['pubDate']);
		}
		static::$_feed = $feed;

		# set date pointer to most recent
		if (empty(static::$_date)) {
			static::$_date = static::date() - 1; // hack to have bot instantly show first feed entry
		}

		# find type to show new entries since last check
		if ($type === 'new') {
			$items = Set::extract('/channel/item[pubDate>' . static::$_date . ']', static::$_feed);
			static::$_date = static::date();
		}

		# if triggered by user, tell them there is nothing to say
		if (!count($items) && !$ping) {
			return array('Nothing new has happened');
		}

		# format the feed items for output
		$items = Set::format($items, '{0} > {1}: {2}', array(
			'/item/author', '/item/title', '/item/link'
		));

		return $items;
	}

	/**
	* @todo datasource
	*/
	public static function read($url) {
		$xml = simplexml_load_file($url);
		$xml = Set::reverse($xml);
		return $xml;
	}

	protected static function date() {
		$date = Set::extract('/channel/item[1]/pubDate', static::$_feed);
		return array_shift($date);
	}
}
?>