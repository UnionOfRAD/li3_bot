<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot\plugins;

use lithium\util\String;

/**
 * Karma plugin.
 */
class Karma extends \li3_bot\extensions\command\bot\Plugin {

	protected $_classes = array(
		'model' => '\li3_bot\models\Karma',
		'response' => '\lithium\console\Response'
	);

	/**
	 * Possible responses,
	 *
	 * @var array
	 */
	protected $_responses = array(
		'update' => "{:recipient} now has karma {:current}.",
		'decrementFail' => "{:recipient} has karma 0, cannot decrement any further.",
		'current' => "{:recipient} has karma {:current}.",
		'self' => "{:user}, you cannot give yourself karma."
	);

	/**
	 * Process incoming messages.
	 *
	 * @param string $data
	 * @return string
	 */
	public function process($data) {
		$model = $this->_classes['model'];
		extract($data);

		if ($message[0] != '~') {
			return;
		}
		list($command, $recipient) = preg_split("/[\s]/", $message, 2) + array(null, null);

		if (!$recipient) {
			return;
		}

		if ($command == '~inc') {
			if ($recipient == $user) {
				return String::insert($this->_responses['self'], compact('user'));
			}
			$model::increment($recipient);
			$current = $model::current($recipient);

			return String::insert($this->_responses['update'], compact('recipient', 'current'));
		} elseif ($command == '~dec') {
			if ($model::current($recipient) == 0) {
				return String::insert($this->_responses['decrementFail'], compact('recipient'));
			}
			$model::decrement($recipient);
			$current = $model::current($recipient);

			return String::insert($this->_responses['update'], compact('recipient', 'current'));
		} elseif ($command == '~karma') {
			$current = $model::current($recipient);

			return String::insert($this->_responses['current'], compact('recipient', 'current'));
		}
	}

}

?>