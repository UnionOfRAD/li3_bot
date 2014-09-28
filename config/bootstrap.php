<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\core\Libraries;

/**
 * Setup default options.
 */
Libraries::add('li3_bot', array('bootstrap' => false) + Libraries::get('li3_bot') + array(
	'host' => 'irc.freenode.net',
	'port' => 6667,
	'channels' => ['#li3-bot'],
	'nick' => 'li3bot',
	// By providing a nick password, you'll automatically enable reclaiming of a nick.
	'password' => null,
	'rewriters' => [],
	'feeds' => []
));

?>