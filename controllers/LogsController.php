<?php

namespace li3_bot\controllers;

use \li3_bot\models\Log;

class LogsController extends \lithium\action\Controller {

	public function index($channel = null) {
		$channels = $logs = null;
		if (empty($channel)) {
			$channels = Log::find('all');
			return compact('channels', 'logs');
		}
		$logs = Log::find('all', compact('channel'));
		natsort($logs);
		$logs = array_reverse($logs); //Logs should show up in reverse chronological order

		return compact('channels', 'channel', 'logs');
	}

	public function view($channel = null, $date = null) {
		$log = Log::read("#{$channel}/{$date}");
		$log = array_reverse($log);
		$this->set(compact('log', 'channel', 'date'));
	}
}

?>
