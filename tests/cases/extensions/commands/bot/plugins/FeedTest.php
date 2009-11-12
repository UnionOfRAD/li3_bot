<?php

namespace li3_bot\tests\cases\extensions\commands\bot\plugins;

use \lithium\console\Request;
use \lithium\console\Response;

class MockFeedModel extends \li3_bot\models\Feed {

	protected static $_config = array('feeds' => array(
		'lithium' => 'http://rad-dev.org/lithium/timeline.rss'
	));
}

class MockFeed extends \li3_bot\extensions\commands\bot\plugins\Feed {

	protected $_classes = array(
		'model' => '\li3_bot\tests\cases\extensions\commands\bot\plugins\MockFeedModel'
	);
}

class FeedTest extends \lithium\test\Unit {

	public function setUp() {
		MockFeedModel::__init();
		$this->feed = new MockFeed(array('init' => false));
		$this->feed->request = new Request(array('input' => fopen('php://temp', 'w+')));
		$this->feed->response = new Response(array(
			'output' => fopen('php://temp', 'w+'),
			'error' => fopen('php://temp', 'w+')
		));

		$this->working = LITHIUM_APP_PATH;
		if (!empty($_SERVER['PWD'])) {
			$this->working = $_SERVER['PWD'];
		}
	}

	public function tearDown() {
		unset($this->feed);
	}

	public function testPoll() {
		$expected = array();
		$result = $this->feed->poll();
		$this->assertEqual($expected, $result);

		$expected = 1;
		$result = $this->feed->poll();
		$this->assertEqual($expected, count($result));

	}
}

?>