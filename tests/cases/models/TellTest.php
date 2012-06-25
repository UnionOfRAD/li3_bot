<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\cases\models;

use lithium\core\Libraries;
use li3_bot\models\Tell;


class TellTest extends \lithium\test\Unit {

	public function skip() {
		$resources = Libraries::get(true, 'resources');
		$path = "{$resources}/tmp/tests";

		if (is_writable($resources) && !is_dir($path)) {
			mkdir($path, 0777, true);
		}

		$this->skipIf(!is_writable($path), "Path `{$path}` is not writable.");
	}

	public function setUp() {
		Tell::$path = Libraries::get(true, 'resources') . '/tmp/tests/test_tells.ini';
	}

	public function tearDown() {
		Tell::reset();
		if (file_exists(Tell::$path)) {
			unlink(Tell::$path);
		}
	}

	public function testSave() {
		$result = Tell::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertTrue($result);
	}

	public function testSaveTwoAndFindAll() {
		$result = Tell::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertTrue($result);

		$expected = array('lithium' => 'http://li3.rad-dev.org');
		$result = Tell::find('all');
		$this->assertEqual($expected, $result);
	}

	public function testSaveAndFind() {
		$result = Tell::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertTrue($result);

		$expected = 'http://li3.rad-dev.org';
		$result = Tell::find('lithium');
		$this->assertEqual($expected, $result);

		$expected = 'http://li3.rad-dev.org';
		$result = Tell::find();
		$this->assertEqual($expected, $result);
	}

	public function testSaveDeleteFind() {
		$result = Tell::save(array('li' => 'the most rad php framework'));
		$this->assertTrue($result);

		Tell::delete('li');

		Tell::reset();

		$result = Tell::find('li');
		$this->assertFalse($result);
	}
}

?>