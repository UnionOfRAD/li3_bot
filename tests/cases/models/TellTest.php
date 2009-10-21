<?php

namespace lithium_bot\tests\cases\models;

use \lithium_bot\models\Tell;

class TellTest extends \lithium\test\Unit {

	public function setUp() {
		Tell::$path = LITHIUM_APP_PATH . '/tmp/test_tells.ini';
	}

	public function tearDown() {
		Tell::reset();
		if (file_exists(Tell::$path)) {
			unlink(Tell::$path);
		}
	}

	public function testSave() {
		$expected = true;
		$result = Tell::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertEqual($expected, $result);
	}

	public function testSaveTwoAndFindAll() {
		$expected = true;
		$result = Tell::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertEqual($expected, $result);

		$expected = array('lithium' => 'http://li3.rad-dev.org');
		$result = Tell::find('all');
		$this->assertEqual($expected, $result);
	}

	public function testSaveAndFind() {
		$expected = true;
		$result = Tell::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertEqual($expected, $result);

		$expected = 'http://li3.rad-dev.org';
		$result = Tell::find('lithium');
		$this->assertEqual($expected, $result);

		$expected = 'http://li3.rad-dev.org';
		$result = Tell::find();
		$this->assertEqual($expected, $result);
	}

	public function testProcessAdd() {
		$expected = 'gwoo, I will remember li';
		$result = Tell::process(array(
			'nick' => 'lithium', 'user' => 'gwoo',
			'message' => 'lithium: li is cool'
		));
		$this->assertEqual($expected, $result);
	}

	public function testProcessNoTell() {
		$expected = 'gwoo, I do not know about li';
		$result = Tell::process(array(
			'nick' => 'lithium', 'user' => 'gwoo',
			'message' => '~tell bob about li'
		));
		$this->assertEqual($expected, $result);
	}

	public function testProcessSimpleTell() {
		$expected = true;
		$result = Tell::save(array('li' => 'cool'));
		$this->assertEqual($expected, $result);

		$expected = 'gwoo, li is cool';
		$result = Tell::process(array(
			'nick' => 'lithium', 'user' => 'gwoo',
			'message' => '~li'
		));
		$this->assertEqual($expected, $result);
	}

	public function testProcessSimpleTellWithSpaces() {
		$expected = true;
		$result = Tell::save(array('li' => 'the most rad php framework'));
		$this->assertEqual($expected, $result);

		Tell::reset();

		$expected = 'gwoo, li is the most rad php framework';
		$result = Tell::process(array(
			'nick' => 'lithium', 'user' => 'gwoo',
			'message' => '~li'
		));
		$this->assertEqual($expected, $result);
	}

	public function testSaveDeleteFind() {
		$expected = true;
		$result = Tell::save(array('li' => 'the most rad php framework'));
		$this->assertEqual($expected, $result);

		$expected = 'gwoo, I forgot about li';
		$result = Tell::process(array(
			'nick' => 'lithium', 'user' => 'gwoo',
			'message' => '~forget li'
		));
		$this->assertEqual($expected, $result);

		Tell::reset();

		$result = Tell::find('li');
		$this->assertFalse($result);
	}

}
?>