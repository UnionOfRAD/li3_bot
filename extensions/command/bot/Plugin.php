<?php

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
	 *     'channel' => '#li3', 'nick'=> 'Li3Bot'
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