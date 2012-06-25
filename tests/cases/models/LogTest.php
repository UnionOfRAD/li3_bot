<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\cases\models;

use lithium\core\Libraries;
use li3_bot\tests\mocks\models\MockLog;

class LogTest extends \lithium\test\Unit {

	public function skip() {
		$resources = Libraries::get(true, 'resources');
		$path = "{$resources}/tmp/tests/logs";

		if (is_writable($resources) && !is_dir($path)) {
			mkdir($path, 0777, true);
		}

		$this->skipIf(!is_writable($path), "Path `{$path}` is not writable.");
	}

	public function tearDown() {
		$this->_cleanUp(Libraries::get(true, 'resources') . '/tmp/tests/logs' );
	}

	public function testSaveAndFind() {
		$result = MockLog::save(array(
			'channel'=> '#li3', 'nick' => 'li3_bot',
			'user' => 'gwoo', 'message' => 'the log message'
		));
		$this->assertTrue($result);

		$expected = array('li3');
		$result = MockLog::find('first');
		$this->assertEqual($expected, $result);

		$expected = array(date('Y-m-d'));
		$result = MockLog::find('all', array('channel' => '#li3'));
		$this->assertEqual($expected, $result);

		$this->assertTrue(is_dir(MockLog::$path . '/li3'));
	}

	public function testRead() {
		$message = 'Some numbers are stripped from the logs? 0 1 2 3 4 5 6 7 8 9';
		MockLog::save(array(
			'channel'=> '#li3',
			'nick' => 'li3_bot',
			'user' => 'mehlah',
			'message' => $message
		));

		$result = MockLog::read('li3', date('Y-m-d'));
		$this->assertEqual($message, $result[0]['message']);

		$message = 'RTFM! http://lithify.me/docs/lithium/template/View::$_steps';
		MockLog::save(array(
			'channel'=> '#li3',
			'nick' => 'li3_bot',
			'user' => 'mehlah_',
			'message' => $message
		));

		$result = MockLog::read('li3', date('Y-m-d'));
		$this->assertEqual($message, $result[1]['message']);

		$message = join(' ', array(
			"\x02lithium\x02", "\x0311∆\x03", "Comment/Fixed/BUG/",
			"\x0311∆\x03", "Strip colors used by li3_bot Feeds plugin", "\x036∆\x03",
			"\x02mehlah\x02", "\x0313∆\x03",
			"https://github.com/UnionOfRAD/li3_bot/commit/a9c32477546927e863a0e737c58f89003bc49254"
		));

		MockLog::save(array(
			'channel'=> '#li3',
			'nick' => 'li3_bot',
			'user' => 'mehlah__',
			'message' => $message
		));

		$expected = join(' ', array(
			"lithium", "∆", "Comment/Fixed/BUG/",
			"∆", "Strip colors used by li3_bot Feeds plugin", "∆",
			"mehlah", "∆",
			"https://github.com/UnionOfRAD/li3_bot/commit/a9c32477546927e863a0e737c58f89003bc49254"
		));
		$result = MockLog::read('li3', date('Y-m-d'));

		$this->assertEqual($expected, $result[2]['message']);
	}
}

?>