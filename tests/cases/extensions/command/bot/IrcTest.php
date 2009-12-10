<?php

namespace li3_bot\tests\cases\extensions\command\bot;

use \li3_bot\extensions\command\bot\Irc;
use \lithium\console\Request;
use \lithium\console\Response;

use \lithium\core\Libraries;

class IrcTest extends \lithium\test\Unit {

	public function setUp() {
		$this->irc = new Irc(array('init' => false));
		$this->irc->request = new Request(array('input' => fopen('php://temp', 'w+')));
		$this->irc->response = new Response(array(
			'output' => fopen('php://temp', 'w+'),
			'error' => fopen('php://temp', 'w+')
		));

		$this->working = LITHIUM_APP_PATH;
		if (!empty($_SERVER['PWD'])) {
			$this->working = $_SERVER['PWD'];
		}
	}

	public function tearDown() {
		unset($this->irc);
	}
	
	public function testRun() {
		
	}
}

?>