<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright	 Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license	   http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\models;

use \lithium\util\String;

class Weather extends \lithium\core\StaticObject {

	static protected $_search  = 'http://api.wunderground.com/auto/wui/geo/GeoLookupXML/index.xml?query=';

	static protected $_station = 'http://api.wunderground.com/auto/wui/geo/WXCurrentObXML/index.xml?query=';

	static public function find($type, $location) {
		if ($type == 'search') {
			$request = static::$_search.$location;
		} else if ($type == 'station') {
			$request = static::$_station.$location;
		} else {
			return false;
		}

		$response = @simplexml_load_file($request);

		if (!$response) {
			return false;
		}

		return $response;
	}
}

?>