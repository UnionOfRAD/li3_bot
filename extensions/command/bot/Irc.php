<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot;

use \lithium\core\Libraries;

class Irc extends \lithium\console\Command {

	public $socket = null;

	protected $_running = false;

	protected $_resource = null;

	protected $_nick = 'li3_bot';

	protected $_password = '';

	protected $_channels = array();

	protected $_joined = array();

	protected $_plugins = array('poll' => array(), 'process' => array());

	protected $_classes = array(
		'socket' => 'lithium\net\socket\Stream',
		'response' => 'lithium\console\Response'
	);

	public function _init() {
		parent::_init();

		$plugin = dirname(dirname(dirname(__DIR__)));
		$this->_config += parse_ini_file($plugin . '/config/li3_bot.ini');
		foreach ($this->_config as $key => $value) {
			$key = "_{$key}";
			if (isset($this->{$key}) && $key !== '_classes') {
				$this->{$key} = $value;
				if ($value && strpos($value, ',') !== false) {
					$this->{$key} = array_map('trim', (array) explode(',', $value));
				}
			}
		}
		$this->socket = $this->_instance('socket', $this->_config);
	}

	public function run() {
		try {
			$this->_running = (boolean) $this->socket->open();
			$this->_resource = $this->socket->resource();
		} catch (Exception $e) {
			$this->out($e);
		}

		if ($this->_running) {
			$this->out('connected');
			$this->_connect();
			$this->_plugins();
		}
		while ($this->_running && !$this->socket->eof()) {
			$this->_process(fgets($this->_resource));
		}
	}

	public function __call($method, $params) {
		if ($method[0] === '_') {
			$value = empty($params) ? $this->{$method} : $params[0];
			$command = strtoupper(ltrim($method, '_')) . " {$value}\r\n";

			return $this->socket->write($command);
		}
	}

	protected function _privmsg($command) {
		if ($this->socket->write("PRIVMSG {$command}\r\n")) {
			$this->_process(":{$this->_nick}!@localhost PRIVMSG {$command}\r\n");
			return true;
		}
	}

	protected function _connect() {
		$this->_nick("{$this->_nick} {$this->_password}");
		$this->_user("{$this->_nick} {$this->_config['host']} Irc bot");
	}

	protected function _process($line) {
		if (stripos($line, 'PING') !== false) {
			list($ping, $pong) = $this->_parse(':', $line, 2);
			$this->_pong($pong);
			foreach ($this->_plugins['poll'] as $class) {
				$responses = $class->poll();
				$this->_respond($this->_channels, $responses);
			}
			return true;
		}
		if ($line[0] === ':') {
			$params = $this->_parse("(\s|(?<=\s):|^:)", $line, 5);

			if (isset($params[2])) {

				$cmd = $params[2];
				$msg = !empty($params[4]) ? $params[4] : null;

				switch ($cmd) {
					case 'PRIVMSG':
						$channel = $params[3];
						$user = $this->_parse("!", $params[1], 3);
						$data = array(
							'channel' => $channel, 'nick'=> $this->_nick,
							'user' => $user[0], 'message' => $msg
						);
						foreach ($this->_plugins['process'] as $class) {
							$responses = $class->process($data);
							$this->_respond($channel, $responses);
						}
					break;

					case '461':
					case '422':
					case '376':
						foreach ((array) $this->_channels as $channel) {
							if (empty($this->_joined[$channel])) {
								$this->_join($channel);
								$this->out("{$this->_nick} joined {$channel}");
								$this->_joined[$channel] = true;
							}
						}
					break;

					case '433': //Nick already registerd
						$this->out($msg);
						$this->_nick = $this->_nick . '_';
						$this->_connect();
					break;

					case '353':
						$this->out('Names on ' . str_replace('=', '', $msg));
					break;

					default:
						$this->out($msg);
					break;
				}
			}
		}
	}

	protected function _plugins() {
		$classes = Libraries::locate('command.bot.plugins');

		foreach ($classes as $class) {
			if (method_exists($class, 'poll')) {
				$this->out("Registering `poll` method from plugin `{$class}`.");
				$this->_plugins['poll'][] = new $class($this->_config);
			}
			if (method_exists($class, 'process')) {
				$this->out("Registering `process` method from plugin `{$class}`.");
				$this->_plugins['process'][] = new $class($this->_config);
			}
		}
	}

	protected function _respond($channels, $responses) {
		if (empty($responses)) {
			return;
		}
		foreach ((array) $channels as $channel) {
			$this->out('Sending ' . count($responses) . " message(s) to channel `{$channel}`:");

			foreach ((array) $responses as $response) {
				$this->out($response);
				$this->_privmsg("{$channel} :{$response}");
			}
		}
	}

	protected function _parse($regex, $string, $offset = -1) {
		return str_replace(array("\r\n", "\n"), '', preg_split("/{$regex}+/", $string, $offset));
	}
}

?>