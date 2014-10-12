<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\controllers;

use lithium\core\Libraries;

use li3_bot\models\LogMessages;
use Exception;

class LogsController extends \lithium\action\Controller {

	public function channels() {
		$channels = LogMessages::channels();

		$breadcrumbs[] = array(
			'title' => 'Channel Logs',
			'url' => array('library' => 'li3_bot', 'controller' => 'logs', 'action' => 'channels')
		);
		return compact('channels', 'breadcrumbs');
	}

	public function index() {
		$channel = '#' . $this->request->channel;
		$year = $this->request->year ?: date('Y');

		if (!in_array($channel, LogMessages::channels())) {
			throw new Exception('Unknown channel.');
		}

		$breadcrumbs[] = array(
			'title' => 'Channel Logs',
			'url' => array('library' => 'li3_bot', 'controller' => 'logs', 'action' => 'channels')
		);
		$breadcrumbs[] = array(
			'title' => $channel,
			'url' => null
		);
		$breadcrumbs[] = array(
			'title' => $year,
			'url' => array(
				'library' => 'li3_bot', 'controller' => 'logs',
				'action' => 'index',
				'channel' => ltrim('#' , $channel)
			) + compact('year')
		);
		$calendar = LogMessages::calendar($channel, $year);

		$previous = $year - 1;
		$next = $year + 1;
		if (!LogMessages::hasYear($channel, $previous)) {
			$previous = null;
		}
		if (!LogMessages::hasYear($channel, $next)) {
			$next = null;
		}

		return compact('channels', 'channel', 'calendar', 'year', 'breadcrumbs', 'next', 'previous');
	}

	public function view() {
		$date = $this->request->date;
		$channel = '#' . $this->request->channel;
		$year = date('Y', strtotime($date));

		if (!in_array($channel, LogMessages::channels())) {
			throw new Exception('Unknown channel.');
		}

		$baseUrl = array('library' => 'li3_bot', 'controller' => 'logs');

		$breadcrumbs[] = array(
			'title' => 'Channel Logs',
			'url' => $baseUrl + array('action' => 'channels')
		);
		$breadcrumbs[] = array(
			'title' => $channel,
			'url' => null
		);
		$breadcrumbs[] = array(
			'title' => $year,
			'url' => array(
				'library' => 'li3_bot', 'controller' => 'logs',
				'action' => 'index',
				'channel' => ltrim('#' , $channel)
			) + compact('year')
		);
		$breadcrumbs[] = array(
			'title' => date('m/d', strtotime($date)),
			'url' => null
		);

		$messages = LogMessages::day($channel, $date);

		$previous = date('Y-m-d', strtotime($date) - (60 * 60 * 24));
		$next = date('Y-m-d', strtotime($date) + (60 * 60 * 24));

		if (!LogMessages::hasDay($channel, $previous)) {
			$previous = null;
		}
		if (!LogMessages::hasDay($channel, $next)) {
			$next = null;
		}
		$rewriters = Libraries::get('li3_bot', 'rewriters');

		return compact('channel', 'messages', 'date', 'breadcrumbs', 'previous', 'next', 'rewriters');
	}
}

?>