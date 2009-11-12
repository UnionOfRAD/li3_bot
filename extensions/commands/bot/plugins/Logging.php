<?php

namespace li3_bot\extensions\commands\bot\plugins;

/**
 * Log plugin
 *
 */
class Logging extends \li3_bot\extensions\commands\bot\Plugin {

	protected $_classes = array(
		'model' => '\li3_bot\models\Log',
		'response' => '\lithium\console\Response'
	);

	/**
	 * log messages
	 *
	 * @param array data
	 * @return array
	 */
	public function process($data) {
		$model = $this->_classes['model'];
		$model::save($data);
	}

}
?>