<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot;

use lithium\analysis\Logger;
use lithium\core\Libraries;
use Exception;

class Irc extends \lithium\console\Command {

	public $socket = null;

	protected $_nick = 'li3_bot';

	protected $_originalNick = 'li3_bot';

	protected $_password = '';

	protected $_channels = array();

	protected $_joined = array();

	protected $_plugins = array('poll' => array(), 'process' => array());

	protected $_classes = array(
		'socket' => 'lithium\net\socket\Stream',
		'response' => 'lithium\console\Response'
	);

	/**
	 * Auto configuration.
	 *
	 * @var array
	 */
	protected $_autoConfig = array('classes' => 'merge', 'nick', 'password', 'channel');

	public function __construct(array $config = array()) {
		parent::__construct($config + Libraries::get('li3_bot'));

		$this->_originalNick = $this->_nick;
	}

	public function _init() {
		parent::_init();

		$this->socket = $this->_instance('socket', array(
			'host' => $this->_config['host'],
			'port' => $this->_config['port']
		));

		foreach (Libraries::locate('command.bot.plugins') as $class) {
			if (method_exists($class, 'poll')) {
				Logger::debug("Registering `poll` method from plugin `{$class}`.");
				$this->_plugins['poll'][] = new $class($this->_config);
			}
			if (method_exists($class, 'process')) {
				Logger::debug("Registering `process` method from plugin `{$class}`.");
				$this->_plugins['process'][] = new $class($this->_config);
			}
		}
	}

	public function run() {
		if (!$this->_connect()) {
			Logger::warning('Failed to connect.');
			return false;
		}
		Logger::debug('Connected :-)');

		if ($pcntl = extension_loaded('pcntl')) {
			Logger::debug('Trapping signals...');
			$this->_trapSignals();
		}
		Logger::debug('Entering loop.');

		$start = time();
		while (is_resource($this->socket->resource())) {
			if ($pcntl) {
				pcntl_signal_dispatch();
			}
			if ($start < time() - (60 * 30)) {
				foreach ($this->_plugins['poll'] as $class) {
					$this->_respond($this->_channels, $class->poll());
				}
				$start = time();
			}

			$r = [$this->socket->resource()];
			$w = [];
			$e = [];
			if (stream_select($r, $w, $e, 2) !== 1) {
				continue;
			}
			$this->_process($this->_read());
		}
		$this->_disconnect();
	}

	/**
	 * Registers signal handlers and handles signals once received.
	 *
	 * @return void
	 */
	protected function _trapSignals() {
		$self = $this;

		$handler = function($number) use ($self) {
			switch ($number) {
				case SIGQUIT:
					$self->_disconnect('Quitting.');
					exit(0);
				default:
				case SIGTERM:
					$self->_disconnect('Terminated.');
					exit(1);
			}
		};
		pcntl_signal(SIGQUIT, $handler);
		pcntl_signal(SIGTERM, $handler);
	}

	protected function _connect() {
		try {
			if (!$this->socket->open()) {
				return false;
			}
		} catch (Exception $e) {
			Logger::warning($e->getMessage());
			return false;
		}

		$this->_write('NICK', "{$this->_nick}");
		$this->_write('USER', "{$this->_nick} {$this->_config['host']} IRC BOT");

		return true;
	}

	protected function _disconnect($message = 'Bye.') {
		Logger::debug('Disconnecting :(');

		if (!is_resource($this->socket->resource())) {
			return true;
		}
		$this->_write('QUIT', $message);
		sleep(1);

		$this->socket->close();
	}

	protected function _read() {
		if ($result = fgets($this->socket->resource())) {
			$this->out('<< {:red}' . rtrim($result) . '{:end}');
			return $result;
		}
		return null;
	}

	protected function _write($command, $params = null) {
		if ($params) {
			$command .= ' ' . $params;
		}
		$command .= "\r\n";

		$this->out('>> {:green}' . rtrim($command) . '{:end}');
		return fwrite($this->socket->resource(), $command);
	}

	protected function _process($line) {
		// The server will check if we're alive. We must reply with a pong.
		if (stripos($line, 'PING') !== false) {
			list($ping, $pong) = $this->_parse(':', $line, 2);
			$this->_write('PONG', $pong);
			return;
		}

		if ($line[0] !== ':') {
			return;
		}
		$params = $this->_parse("(\s|(?<=\s):|^:)", $line, 5);

		if (!isset($params[2])) {
			return null;
		}
		$cmd = $params[2];
		$msg = !empty($params[4]) ? $params[4] : null;

		switch ($cmd) {
			case 'PRIVMSG':
				$channel = $params[3];
				$user = $this->_parse("!", $params[1], 3);

				$data = array(
					'channel' => $channel,
					'nick'=> $this->_nick,
					'user' => $user[0],
					'message' => $msg
				);
				foreach ($this->_plugins['process'] as $class) {
					$this->_respond((array) $channel, $class->process($data));
				}
			break;

			case '461':
			case '422':
			case '376':
				foreach ($this->_channels as $channel) {
					if (empty($this->_joined[$channel])) {
						$this->_write('JOIN', $channel);
						$this->_joined[$channel] = true;

						Logger::debug("Bot `{$this->_nick}` joined channel `{$channel}`.");
					}
				}
			break;

			case '433': // Nick already registerd
				Logger::debug("Nick {$this->_nick} already in use.");

				$this->_disconnect();
				$this->_nick .= '_';
				$this->_connect();

			break;

			case '353':
				// Logger::debug('Names on ' . str_replace('=', '', $msg) . '.');
			break;

			case 'NOTICE':
				if (preg_match('/identify.*NickServ.*identify/', $msg) && $this->_password) {
					Logger::debug("Identifying as {$this->_nick}.");
					$this->_write('PRIVMSG', "NickServ :IDENTIFY {$this->_nick} {$this->_password}");
				}
			break;

			case 'MODE':
				if ($msg === '+i') { // We're now identified.
					if ($this->_password && $this->_nick !== $this->_originalNick) {
						$this->_nick = $this->_originalNick;
						Logger::debug("Reclaiming nick {$this->_nick}.");

						$this->_write('PRIVMSG', "NickServ :GHOST {$this->_nick} {$this->_password}");
						sleep(2);
						$this->_write('PRIVMSG', "NickServ :RELEASE {$this->_nick} {$this->_password}");
						sleep(2);
						$this->_write('PRIVMSG', "NickServ :IDENTIFY {$this->_nick} {$this->_password}");
					}
				}
			default:
				return;
			break;
		}
	}

	protected function _parse($regex, $string, $offset = -1) {
		return str_replace(array("\r\n", "\n"), '', preg_split("/{$regex}+/", $string, $offset));
	}

	protected function _respond($channels, $responses) {
		if (empty($responses)) {
			return;
		}
		foreach ($channels as $channel) {
			Logger::debug('Responding with ' . count($responses) . " message(s) to channel `{$channel}`:");

			foreach ((array) $responses as $response) {
				if ($this->_write('PRIVMSG', $command = "{$channel} :{$response}")) {
					$this->_process(":{$this->_nick}!@localhost PRIVMSG {$command}\r\n");
				}
			}
		}
	}
}

?>