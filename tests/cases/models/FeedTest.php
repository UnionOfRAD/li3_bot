<?php

namespace li3_bot\tests\cases\models;

class MockFeed extends \li3_bot\models\Feed {

	protected static $_config = array('feeds' => array(
		'lithium' => 'http://rad-dev.org/lithium/timeline.rss'
	));
}

class FeedTest extends \lithium\test\Unit {

	public function setUp() {
	}

	public function tearDown() {
		MockFeed::reset();
	}

	public function testFind() {
		$expected = array();
		$result = MockFeed::find('first');
		$this->assertEqual($expected, $result);

		$expected = 1;
		$result = MockFeed::find('new', array('ping' => false, 'name' => 'lithium'));
		$this->assertEqual($expected, count($result));

		$expected = 4;
		$result = MockFeed::find('all', array('name' => 'lithium'));
		$this->assertEqual($expected, count($result));
	}

}
?>