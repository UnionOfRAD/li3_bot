<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\mocks\models;

class MockKarma extends \li3_bot\models\Karma {

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/resources/tmp/tests/test_karmas.ini';
	}
}

?>