<?php

namespace li3_bot\extensions\commands\bot;

use \lithium\util\socket\Stream;

use \li3_bot\models\Tell;
use \li3_bot\models\Log;

class Irc extends \lithium\console\Command {

	public $socket = null;

	protected $_run= false;

	protected $_resource = null;

	protected $_nick = 'li3_bot';

	protected $_channels = array();

	public function _init() {
		parent::_init();
		$plugin = dirname(dirname(dirname(__DIR__)));
		$this->_config += parse_ini_file($plugin . '/config/li3_bot.ini');
		foreach ($this->_config as $key => $value) {
			$key = "_{$key}";
			if (isset($this->{$key})) {
				$this->{$key} = $value;
				if ($value && strpos($value, ',') !== false) {
					$this->{$key} = explode(',', $value);
				}
			}
		}
		$this->socket = new Stream($this->_config);
	}

	public function run() {
		try {
			$this->_run = (bool) $this->socket->open();
			$this->_resource = $this->socket->resource();
		} catch (Exception $e) {
			$this->out($e);
		}

		if ($this->_run) {
			$this->out('connected');
			$this->_connect();
		}

		while($this->_run && !$this->socket->eof()) {
			$this->_process();
		}
	}

	public function __call($method, $params) {
		if ($method[0] === '_') {
			$value = empty($params) ? $this->{$method} : $params[0];
			$command = strtoupper(ltrim($method, '_')) . " {$value} \r\n";
			$this->out($command);
			return $this->socket->write($command);
		}
	}

	protected function _connect() {
		$this->_nick();
		$this->_user("{$this->_nick} {$this->_config['host']} botts :{$this->_nick}");
	}

	protected function _process() {
		$line =	 fgets($this->_resource);

		if (stripos($line, 'PING') !== false) {
			list($ping, $pong) = $this->_parse(':', $line, 2);
			$this->_pong($pong);
		} elseif ($line{0} === ':') {
			$params = $this->_parse("\s:", $line, 5);

			if (isset($params[2])) {

				$cmd = $params[2];
				$msg = $params[4];

				$this->out($cmd);

				switch ($cmd) {
					case 'PRIVMSG':
						$channel = $params[3];
						$user = $this->_parse("!", $params[1], 3);
						$response = $this->_response(array(
							'nick'=> $this->_nick, 'user' => $user[0],
							'message' => $msg
						));
						if($response) {
							$this->socket->write("PRIVMSG {$channel} :{$response}\r\n");
						}
					break;

					case '461':
					case '422':
					case '376':
						foreach ((array)$this->_channels as $channel) {
							$this->_join($channel);
							$this->out("{$this->_nick} joined {$channel}");
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
		} else {
			$this->out($line);
			/*
			while($cmd = $this->in('enter an irc command')) {
				// /$this->write($cmd);
			}
			*/
		}
	}

	protected function _response($data) {
		$tell = Tell::process($data);
		$this->out($tell);
		if ($tell) {
			return $tell;
		}
		Log::save(date('H:i:s') . " : {$data['user']} : {$data['message']}\n");
	}

	protected function _parse($regex, $string, $offset = -1) {
		return str_replace(array("\r\n", "\n"), '', preg_split("/[{$regex}]+/", $string, $offset));
	}
}

?>