<?php

namespace lithium_bot\extensions\commands;

use \lithium\util\socket\Stream;

class Bot extends \lithium\console\Command {
	
	public function run() {
		$bot = new \lithium_bot\extensions\commands\bot\Irc();
		return $bot->run();
	}
}

?>