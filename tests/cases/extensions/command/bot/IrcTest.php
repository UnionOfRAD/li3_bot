<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\tests\cases\extensions\command\bot;

use lithium\console\Request;
use lithium\console\Response;
use li3_bot\tests\mocks\extensions\command\bot\MockIrc;

class IrcTest extends \lithium\test\Unit {

	public function setUp() {
		$this->irc = new MockIrc(array(
			'request' => new Request(array('input' => fopen('php://temp', 'w+'))),
			'host' => 'localhost',
			'classes' => array(
				'socket' => 'li3_bot\tests\mocks\extensions\command\MockIrcStream',
				'response' => 'lithium\tests\mocks\console\MockResponse'
			)
		));
	}

	public function tearDown() {
		unset($this->irc);
	}

	public function testRun() {
		$result = $this->irc->run();
		$resource = $this->irc->socket->resource();
		rewind($resource);

		$expected = "connected\n";

		$result = $this->irc->response->output;
		$this->assertTrue(strpos($result, $expected) !== false);

		$expected = "NICK li3_bot\r\nUSER li3_bot localhost botts :li3_bot\r\n";
		$result = fread($resource, 1024);
		$this->assertEqual($expected, $result);
	}

	public function testParseMessage() {
		$line = ':nperson!~davidpers@e176014212.adsl.example.org PRIVMSG #li3_bot :hello';
		$result = $this->irc->parse("(\s|(?<=\s):|^:)", $line, 5);
		$expected = array(
			0 => '',
			1 => 'nperson!~davidpers@e176014212.adsl.example.org',
			2 => 'PRIVMSG',
			3 => '#li3_bot',
			4 => 'hello'
		);
		$this->assertEqual($expected, $result);

		$line = ':nperson!~davidpers@e176014212.adsl.example.org PRIVMSG #li3_bot :This test.';
		$result = $this->irc->parse("(\s|(?<=\s):|^:)", $line, 5);
		$this->assertEqual('This test.', $result[4]);

		$line = ':nperson!~davidpers@e176014212.adsl.example.org PRIVMSG #li3_bot ::)';
		$result = $this->irc->parse("(\s|(?<=\s):|^:)", $line, 5);
		$this->assertEqual(':)', $result[4]);
	}

	public function testProcess() {
		$this->irc->run();
		$resource = $this->irc->socket->resource();
		rewind($resource);
		fwrite($resource, 'something');
		rewind($resource);
		$result = $this->irc->process();

		$expected = "connected\n";
		$result = $this->irc->response->output;
		$this->assertTrue(strpos($result, $expected) !== false);

		$expected = "something";
		$result = fread($resource, 1024);
		$this->assertEqual($expected, $result);
	}
}

?>