<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command;

use li3_bot\extensions\command\bot\Irc;

/**
 * A set of commands to start and control the Lithium Bot.
 */
class Bot extends \lithium\console\Command {

	/**
	 * The main method of the command.
	 *
	 * @return void
	 */
	public function run() {
		return $this->irc();
	}

	/**
	 * Starts the IRC bot, which will connect to servers and channels as
	 * defined in `config/li3_bot.ini`. Will also run all active plugins.
	 *
	 * The IRC bot will enter a while loop and exit only upon user
	 * interruption. To stop the bot hit `STRG+C`.
	 *
	 * @return boolean
	 */
	public function irc() {
		$command = new Irc(array('request' => $this->request));
		return $command->run();
	}
}

?>