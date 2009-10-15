<?php

namespace lithium_bot\extensions\commands\bot;

use \lithium\util\socket\Stream;

use \lithium_bot\models\Log;

class Irc extends \lithium\console\Command {

	public $socket = null;

	protected $_run= false;

	protected $_resource = null;

	protected $_nick = 'lithium_bot';

	protected $_channels = array();

	public function _init() {
		parent::_init();
		$plugin = dirname(dirname(dirname(__DIR__)));
		$this->_config += parse_ini_file($plugin . '/config/lithium_bot.ini');
		foreach ($this->_config as $key => $value) {
			$key = "_{$key}";
			if (isset($this->{$key})) {
				$this->{$key} = $value;
				if (strpos($value, ',')) {
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
			$this->_response();
		}
	}

	public function __call($method, $params) {
		if ($method[0] === '_') {
			$value = empty($params) ? $this->{$method} : $params[0];
			return $this->socket->write(strtoupper(ltrim($method, '_')) . " {$value} \r\n");
		}
	}

	protected function _connect() {
		$this->_nick();
		$this->_user("{$this->_nick} {$this->_config['host']} botts : {$this->_nick}");
		foreach ((array)$this->_channels as $channel) {
			$this->_join($channel);
			$this->out("{$this->_nick} joined {$channel}");
		}
		return true;
	}

	protected function _response() {
		$line =  fgets($this->_resource, 128);

		if (stripos($line, 'PING') !== false) {
			list($ping, $pong) = $this->_parse(':', $line, 2);
	        $this->out($ping);
	        if (isset($pong)) {
				$this->out('PONG');
	            $this->socket->write("PONG " . $pong);
	        }
		} elseif ($line{0} === ':') {
			$params = $this->_parse("\s:", $line, 5);

			if (isset($params[2])) {

				$cmd = $params[2];
				$msg = $params[4];

				switch ($cmd) {
					case 'PRIVMSG':
						$channel = $params[3];
						$user = $this->_parse("!", $params[1], 3);
						$this->_user = $user[0];
						$this->out($msg);
						if($msg = $this->_message($user[0], $msg)) {
							$this->socket->write("PRIVMSG {$channel} :{$msg}\r\n");
						}
					break;
					case '433': //Nick already registerd
						$this->out($msg);
						$this->_nick = $this->_nick . '_';
						$this->join();
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

	protected function _message($user, $msg) {
		Log::save(date('H:i:s') . ": {$user}: {$msg}\n");
	}

	protected function _parse($regex, $string, $offset = -1) {
		return str_replace(array("\r\n", "\n"), '', preg_split("/[{$regex}]+/", $string, $offset));
	}
}

?>