<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command;

use li3_bot\extensions\command\bot\Irc;
use li3_bot\models\Tells;
use li3_bot\models\Logs;
use li3_bot\models\LogMessages;


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

	public function migrate() {
		$ini = parse_ini_file('/Users/mariuswilms/Code/lithium/lithium_site/app/resources/bot/tells.ini');

		foreach ($ini as $key => $value) {
					$item = Tells::create([
						'created' => date('Y-m-d H:i:s'),
					] + compact('key', 'value'));
					$item->save();
			}
	}

	public function migrate_logs() {
		$old = Logs::find('all', ['channel' => 'li3']);

		foreach (Logs::find('all') as $channel) {
			foreach (Logs::find('all', compact('channel')) as $date) {
				$lines = Logs::read($channel, $date);

				foreach ($lines as $line) {
					$item = LogMessages::create([
						'created' => $date . ' ' . $line['time'],
						'user' => $line['user'],
						'channel' => $channel,
						'message' => $line['message']
					]);
					$item->save();
				}

			}
		}
	}
}

?>