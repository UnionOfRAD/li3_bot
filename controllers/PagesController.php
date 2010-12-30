<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\controllers;

use li3_bot\models\Log;
use li3_bot\models\Tell;
use li3_bot\models\Karma;
use lithium\core\Libraries;

class PagesController extends \lithium\action\Controller {

	public function home() {
		$channels = Log::find('all');
		$tells = Tell::recent();
		$karmas = Karma::highscore();
		$plugins = Libraries::locate('command.bot.plugins');

		return compact('channels', 'plugins', 'tells', 'karmas');
	}
}

?>