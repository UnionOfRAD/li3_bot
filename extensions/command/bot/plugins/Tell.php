<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot\plugins;

use \lithium\util\String;

/**
 * Tell plugin
 *
 */
class Tell extends \li3_bot\extensions\command\bot\Plugin {

	protected $_classes = array(
		'model' => '\li3_bot\models\Tell',
		'response' => '\lithium\console\Response'
	);

	/**
	 * possible responses
	 *
	 * @var array
	 */
	protected $_responses = array(
		'forgot' => '{:user}, I forgot about {:tell}.',
		'forget_unknown' => '{:user}, I never knew about {:tell}.',
		'success' => '{:user}, {:tell} is {:answer}.',
		'known' => '{:user}, I thought {:tell} was {:answer}.',
		'remember' => '{:user}, I will remember {:tell}.',

	);
	/**
	 * Process incoming messages
	 *
	 * @param string $data
	 * @return string
	 */
	public function process($data) {
		$responses = $this->_responses;
		$model = $this->_classes['model'];
		$tells = $model::find('all');
		$key = null;
		extract($data);

		if ($message[0] == '~') {
			$words = preg_split("/[\s]/", $message, 4);

			if ($words[0] == '~tell') {
				if ($words[2] == 'about') {
					$key = $words[3];
					$to = $words[1];
				}
			} else {
				$key = ltrim($words[0], '~');
				$to = $user;

				if ($key == 'forget') {
					$response = $this->_forget($words[1]);
					return String::insert($response, array(
						'user' => $to, 'tell' => $words[1]
					));
				}
			}
			if (!isset($tells[$key])) {
				/* Not catching unkown tells, those could as well be other commands. */
				return;
			}
			return String::insert($responses['success'], array(
				'user' => $to, 'tell' => $key, 'answer' => $tells[$key]
			));
		}
		if (stripos($message, $nick) !== false) {
			$words = preg_split("/[\s]/", $message, 4);

			if ($words[1] == 'forget') {
				$response = $this->_forget($words[2]);
				return String::insert($response, array(
					'user' => $user, 'tell' => $words[2]
				));
			}

			if (!empty($words[2]) && $words[2] == 'is') {
				if (isset($tells[$words[1]])) {
					$answer = $tells[$words[1]];
					return String::insert($responses['known'], array(
						'user' => $user, 'tell' => $words[1], 'answer' => $answer
					));
				}
				if ($model::save(array($words[1] => $words[3]))) {
					return String::insert($responses['remember'], array(
						'user' => $user, 'tell' => $words[1]
					));
				}
			}
		}
	}

	protected function _forget($tell) {
		$model = $this->_classes['model'];
		$response = $this->_responses['forget_unknown'];

		if ($model::delete($tell)) {
			$response = $this->_responses['forgot'];
		}
		return $response;
	}
}

?>