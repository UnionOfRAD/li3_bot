<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\extensions\command\bot\plugins;

/**
 * Log plugin
 *
 */
class Logging extends \li3_bot\extensions\command\bot\Plugin {

	protected $_classes = array(
		'model' => '\li3_bot\models\Log',
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

		if (in_array($data['channel'], $this->_config['channels'])) {
			$model::save($data);
		}
	}
}

?>