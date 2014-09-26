<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\models;

use lithium\core\Libraries;

class Tells extends \lithium\data\Model {

	public static function recent() {
		return static::find('all', [
			'order' => ['created' => 'DESC'],
			'limit' => 10
		]);
	}
}

?>