<?php

namespace li3_bot\extensions\commands;

use \lithium\util\socket\Stream;

class Bot extends \lithium\console\Command {
	
	public function run() {
		$bot = new \li3_bot\extensions\commands\bot\Irc();
		return $bot->run();
	}
}

?>