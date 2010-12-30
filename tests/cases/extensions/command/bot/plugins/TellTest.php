<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\cases\extensions\command\bot\plugins;

use lithium\console\Request;
use lithium\console\Response;
use li3_bot\tests\mocks\models\MockTell as MockTellModel;
use li3_bot\tests\mocks\extensions\command\bot\plugins\MockTell;

class TellTest extends \lithium\test\Unit {

	public function setUp() {
		MockTellModel::__init();
		$this->tell = new MockTell(array('init' => false));
		$this->tell->request = new Request(array('input' => fopen('php://temp', 'w+')));
		$this->tell->response = new Response(array(
			'output' => fopen('php://temp', 'w+'),
			'error' => fopen('php://temp', 'w+')
		));

		$this->working = LITHIUM_APP_PATH;
		if (!empty($_SERVER['PWD'])) {
			$this->working = $_SERVER['PWD'];
		}
	}

	public function tearDown() {
		unset($this->tell);
		$this->_cleanUp();
	}

	public function testProcess() {
		$expected = 'gwoo, I will remember lithium.';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'li3_bot',
		 	'user' => 'gwoo', 'message' => 'li3_bot: lithium is cool'
		));
		$this->assertEqual($expected, $result);

		$expected = 'gwoo, lithium is cool.';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'li3_bot',
		 	'user' => 'gwoo', 'message' => '~lithium'
		));
		$this->assertEqual($expected, $result);

		$expected = 'bob, lithium is cool.';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'li3_bot',
		 	'user' => 'gwoo', 'message' => '~tell bob about lithium'
		));
		$this->assertEqual($expected, $result);
	}

	public function testForget() {
		MockTellModel::reset();
		$expected = 'gwoo, I will remember lithium.';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'li3_bot',
		 	'user' => 'gwoo', 'message' => 'li3_bot: lithium is cool'
		));
		$this->assertEqual($expected, $result);

		$expected = 'gwoo, I forgot about lithium.';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'li3_bot',
		 	'user' => 'gwoo', 'message' => '~forget lithium'
		));
		$this->assertEqual($expected, $result);

		$expected = 'gwoo, I never knew about lithium.';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'li3_bot',
		 	'user' => 'gwoo', 'message' => '~forget lithium'
		));
		$this->assertEqual($expected, $result);
	}
}

?>