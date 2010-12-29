<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\controllers;

use li3_bot\models\Tell;

class TellsController extends \lithium\action\Controller {

	public function index() {
		$tells = Tell::find('all');
		return compact('tells');
	}
}

?>