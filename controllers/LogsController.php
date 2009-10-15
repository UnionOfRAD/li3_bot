<?php

namespace lithium_bot\controllers;

use \lithium_bot\models\Log;

class LogsController extends \lithium\action\Controller {
	
	public function index() {
		$logs = Log::all();
		$this->set(compact('logs'));
	}
	
	public function view($date = null) {
		if (!$date) {
			$date = date('Y-m-d');
		}
		$log = Log::read($date);
		$this->set(compact('log'));
	}
}

?>