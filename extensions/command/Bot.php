<?php

namespace li3_bot\extensions\command;

class Bot extends \lithium\console\Command {

	public function run() {
		$bot = new \li3_bot\extensions\command\bot\Irc();
		return $bot->run();
	}
}

?>