<?php

namespace li3_bot\tests\cases\models;

use \li3_bot\models\Feed;

class FeedTest extends \lithium\test\Unit {

	public function setUp() {
	}

	public function tearDown() {
		Feed::reset();
	}

	public function testPoll() {
		$expected = array();
		$result = Feed::poll(true);
		$this->assertEqual($expected, $result);

		$expected = array();
		$result = Feed::poll(true);
		$this->assertNotEqual($expected, $result);

	}

}
?>