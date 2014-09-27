<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot\plugins;

use lihtium\analyis\Logger;

/**
 * Log plugin
 *
 */
class Logging extends \li3_bot\extensions\command\bot\Plugin {

	protected $_classes = array(
		'model' => '\li3_bot\models\LogMessages',
		'response' => '\lithium\console\Response'
	);

	protected $_config = array();

	public function __construct($config) {
		$this->_config = $config;
		parent::__construct($config);
	}

	/**
	 * Logs messages. Will log only configured channels.
	 *
	 * @param array data
	 * @return void
	 */
	public function process($data) {
		$model = $this->_classes['model'];

		if (!in_array($data['channel'], $this->_config['channels'])) {
			Logger::notice("Tried to log for non-whitelisted channel `{$data['chanel']}`.");
			return;
		}
		$colorCodes = '[\x02\x1F\x0F\x16]|\x03(\d\d?(,\d\d?)?)?';
		$data['message'] = preg_replace("/{$colorCodes}/", null, $data['message']);

		$item = $model::create([
			'channel' => $data['channel'],
			'user' => $data['user'],
			'message' => $data['message'],
			'created' => date('Y-m-d H:i:s')
		]);
		if (!$item->save()) {
			Logger::notice('Failed to save log message.');
		}
	}
}

?>