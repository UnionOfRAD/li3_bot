<?php

namespace li3_bot\controllers;

use \li3_bot\models\Log;

class LogsController extends \lithium\action\Controller {

	public function index($channel = null) {
		if (empty($channel)) {
			$channels = Log::find('all');
			return compact('channels');
		}
		$logs = Log::find('all', compact('channel'));
		return compact('channel', 'logs');
	}

	public function view($channel = null, $date = null) {
		$log = Log::read('#' . $channel .'/' . $date);
		$this->set(compact('log'));
	}
}

?>