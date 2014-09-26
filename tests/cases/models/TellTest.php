<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\cases\models;

use lithium\core\Libraries;
use li3_bot\models\Tells;

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
		Tells::$path = Libraries::get(true, 'resources') . '/tmp/tests/test_tells.ini';
	}

	public function tearDown() {
		Tells::reset();
		if (file_exists(Tells::$path)) {
			unlink(Tells::$path);
		}
	}

	public function testSave() {
		$result = Tells::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertTrue($result);
	}

	public function testSaveTwoAndFindAll() {
		$result = Tells::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertTrue($result);

		$expected = array('lithium' => 'http://li3.rad-dev.org');
		$result = Tells::find('all');
		$this->assertEqual($expected, $result);
	}

	public function testSaveAndFind() {
		$result = Tells::save(array('lithium' => 'http://li3.rad-dev.org'));
		$this->assertTrue($result);

		$expected = 'http://li3.rad-dev.org';
		$result = Tells::find('lithium');
		$this->assertEqual($expected, $result);

		$expected = 'http://li3.rad-dev.org';
		$result = Tells::find();
		$this->assertEqual($expected, $result);
	}

	public function testSaveDeleteFind() {
		$result = Tells::save(array('li' => 'the most rad php framework'));
		$this->assertTrue($result);

		Tells::delete('li');

		Tells::reset();

		$result = Tells::find('li');
		$this->assertFalse($result);
	}
}

?>