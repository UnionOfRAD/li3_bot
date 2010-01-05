<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command;

class Bot extends \lithium\console\Command {

	public function run() {
		$bot = new \li3_bot\extensions\command\bot\Irc();
		return $bot->run();
	}
}

?>