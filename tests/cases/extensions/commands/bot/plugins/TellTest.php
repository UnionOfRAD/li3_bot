<?php

namespace li3_bot\tests\cases\extensions\commands\bot\plugins;

use \lithium\console\Request;
use \lithium\console\Response;

class MockTellModel extends \li3_bot\models\Tell {

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/tmp/test_tells.ini';
	}
}

class MockTell extends \li3_bot\extensions\commands\bot\plugins\Tell {

	protected $_classes = array(
		'model' => '\li3_bot\tests\cases\extensions\commands\bot\plugins\MockTellModel'
	);
}

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
		unlink(MockTellModel::$path);
	}

	public function testProcess() {
		$expected = 'gwoo, I do not know about cool';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'Li3Bot',
		 	'user' => 'gwoo', 'message' => '~cool'
		));
		$this->assertEqual($expected, $result);

		$expected = 'gwoo, I will remember lithium';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'Li3Bot',
		 	'user' => 'gwoo', 'message' => 'Li3Bot: lithium is cool'
		));
		$this->assertEqual($expected, $result);

		$expected = 'gwoo, lithium is cool';
		$result = $this->tell->process(array(
		 	'channel' => '#li3', 'nick'=> 'Li3Bot',
		 	'user' => 'gwoo', 'message' => '~lithium'
		));
		$this->assertEqual($expected, $result);

	}
}

?>