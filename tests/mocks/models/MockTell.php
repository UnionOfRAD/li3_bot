<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\mocks\models;

use lithium\core\Libraries;

class MockTell extends \li3_bot\models\Tells {

	public static function __init() {
		static::$path = Libraries::get(true, 'resources') . '/tmp/tests/test_tells.ini';
	}
}

?>