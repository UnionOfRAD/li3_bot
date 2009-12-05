<?php

namespace li3_bot\extensions\command\bot\plugins;

/**
 * Feed plugin
 *
 */
class Feed extends \li3_bot\extensions\command\bot\Plugin {

	protected $_classes = array(
		'model' => '\li3_bot\models\Feed',
		'response' => '\lithium\console\Response',
	);

	/**
	 * One each ping find new feeds
	 *
	 * @return array
	 */
	public function poll() {
		$model = $this->_classes['model'];
		$responses = array();
		$config = $model::config();
		foreach ($config['feeds'] as $name => $path) {
			$responses = array_merge($responses, $model::find('new', compact('name', 'path')));
		}
		return $responses;
	}
}
?>