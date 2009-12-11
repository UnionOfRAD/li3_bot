<?php

namespace li3_bot\tests\cases\models;

class MockLog extends \li3_bot\models\Log {

	public static function __init() {
		static::$path = LITHIUM_APP_PATH . '/resources/tmp/tests/logs/';
		if (!is_dir(static::$path)) {
			mkdir(static::$path, 0777, true);
		}
	}
}

class LogTest extends \lithium\test\Unit {

	public function setUp() {
		MockLog::__init();
	}

	public function tearDown() {
		array_filter(glob(MockLog::$path .'#li3/*'), function ($file) {
			return unlink($file);
		});
	}

	public function testSave() {
		$expected = true;
		$result = MockLog::save(array(
			'channel'=> '#li3', 'nick' => 'Li3Bot',
			'user' => 'gwoo', 'message' => 'the log message'
		));
		$this->assertEqual($expected, $result);

		$expected = array('#li3');
		$result = MockLog::find('first');
		$this->assertEqual($expected, $result);

		$expected = array(date('Y-m-d'));
		$result = MockLog::find('all', array('channel' => '#li3'));
		$this->assertEqual($expected, $result);
	}

}
?>