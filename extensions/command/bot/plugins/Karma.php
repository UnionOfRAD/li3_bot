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
		'update' => "{:user} now has karma {:current}.",
		'decrementFail' => "{:user} has karma 0, cannot decrement any further.",
		'current' => "{:user} has karma {:current}.",
		'usage' => "Need a nick. Try: `~inc|dec|karma <NICK>`."
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
		list($command, $user) = preg_split("/[\s]/", $message, 2);

		if (!$user) {
			return $this->_responses['usage'];
		}

		if ($command == '~inc') {
			$model::increment($user);
			$current = $model::current($user);
			return String::insert($this->_responses['update'], compact('user', 'current'));
		} elseif ($command == '~dec') {
			if ($a = $model::current($user) == 0) {
				return String::insert($this->_responses['decrementFail'], compact('user'));
			}
			$model::decrement($user);
			$current = $model::current($user);

			return String::insert($this->_responses['update'], compact('user', 'current'));
		} elseif ($command == '~karma') {
			$current = $model::current($user);

			return String::insert($this->_responses['current'], compact('user', 'current'));
		}
	}
}

?>