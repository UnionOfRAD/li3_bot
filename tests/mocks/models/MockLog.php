<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\mocks\models;

class MockLog extends \li3_bot\models\Log {

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/resources/tmp/tests/logs';
		if (!is_dir(static::$path)) {
			mkdir(static::$path, 0777, true);
		}
	}
}

?>
