<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\cases\models;

use li3_bot\tests\mocks\models\MockLog;

class LogTest extends \lithium\test\Unit {

	public function setUp() {
		MockLog::__init();
	}

	public function tearDown() {
		$this->_cleanUp();
	}

	public function testSaveAndFind() {
		$expected = true;
		$result = MockLog::save(array(
			'channel'=> '#li3', 'nick' => 'li3_bot',
			'user' => 'gwoo', 'message' => 'the log message'
		));
		$this->assertEqual($expected, $result);

		$expected = array('li3');
		$result = MockLog::find('first');
		$this->assertEqual($expected, $result);

		$expected = array(date('Y-m-d'));
		$result = MockLog::find('all', array('channel' => '#li3'));
		$this->assertEqual($expected, $result);

		$this->assertTrue(is_dir(MockLog::$path . '/li3'));
	}
}

?>