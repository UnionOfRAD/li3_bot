<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\mocks\extensions\command\bot\plugins;

class MockTell extends \li3_bot\extensions\command\bot\plugins\Tell {

	protected $_classes = array(
		'model' => '\li3_bot\tests\mocks\models\MockTell'
	);
}

?>