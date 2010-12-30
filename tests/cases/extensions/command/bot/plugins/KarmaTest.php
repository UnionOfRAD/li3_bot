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
use li3_bot\tests\mocks\models\MockKarma as MockKarmaModel;
use li3_bot\tests\mocks\extensions\command\bot\plugins\MockKarma;

class KarmaTest extends \lithium\test\Unit {

	public function setUp() {
		MockKarmaModel::__init();

		$this->karma = new MockKarma(array('init' => false));
		$this->karma->request = new Request(array('input' => fopen('php://temp', 'w+')));
		$this->karma->response = new Response(array(
			'output' => fopen('php://temp', 'w+'),
			'error' => fopen('php://temp', 'w+')
		));

		$this->working = LITHIUM_APP_PATH;
		if (!empty($_SERVER['PWD'])) {
			$this->working = $_SERVER['PWD'];
		}
	}

	public function tearDown() {
		unset($this->karma);
		$this->_cleanUp();
	}

	public function testKarmaStatus() {
		$expected = 'bob has karma 0.';
		$result = $this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~karma bob'
		));
		$this->assertEqual($expected, $result);
	}

	public function testIncrementKarma() {
		$expected = 'bob now has karma 1.';
		$result = $this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~inc bob'
		));
		$this->assertEqual($expected, $result);

		$expected = 'bob now has karma 2.';
		$result = $this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~inc bob'
		));
		$this->assertEqual($expected, $result);
	}

	public function testSelfKarma() {
		$expected = 'nperson, you cannot give yourself karma.';
		$result = $this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~inc nperson'
		));
		$this->assertEqual($expected, $result);
	}

	public function testDecrementKarma() {
		$this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~inc bob'
		));
		$this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~inc bob'
		));

		$expected = 'bob now has karma 1.';
		$result = $this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~dec bob'
		));
		$this->assertEqual($expected, $result);

		$this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~dec bob'
		));

		$expected = 'bob has karma 0, cannot decrement any further.';
		$result = $this->karma->process(array(
			'channel' => '#li3', 'nick'=> 'li3_bot',
			'user' => 'nperson', 'message' => '~dec bob'
		));
		$this->assertEqual($expected, $result);
	}
}

?>