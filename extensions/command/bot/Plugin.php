<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot;

/**
 * Abstract class for Bot plugins
 *
 */
abstract class Plugin extends \lithium\console\Command {
	
	/**
	 * Called on each ping
	 *
	 * @return string
	 */
	//abstract public function poll();
	
	/**
	 * Process messages and message output 
	 * {{{
	 * $data = array(
	 *     'channel' => '#li3', 'nick'=> 'li3_bot'
	 *     'user' => 'gwoo', 'message' => '~cool'
	 * );
	 * }}}
	 *
	 * @param array $data 
	 * @return string
	 */
	//abstract public function process($data);
}
?>