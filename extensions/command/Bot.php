<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command;

/**
 * A bot class for running agaist various servers.
 * Includes an IRC and plugins.
 * `li3 Bot irc`
 *
 */
class Bot extends \lithium\console\Command {

	public function run() {
		return $this->irc();
	}

	/**
	 * Run IRC bot
	 *
	 */
	public function irc() {
		$bot = new \li3_bot\extensions\command\bot\Irc();
		return $bot->run();
	}
}

?>