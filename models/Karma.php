<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2014, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_bot\models;

class Karma extends \lithium\data\Model {

	protected $_meta = array(
		'source' => 'karma'
	);

	public static function current($user) {
		$item = static::find('first', ['conditions' => compact('user')]);

		if (!$item) {
			return 0;
		}
		return $item->score;
	}

	public static function highscore() {
		return static::find('all', [
			'order' => ['score' => 'DESC'],
			'limit' => 10
		]);
	}

	public static function increment($user) {
		$item = static::find('first', ['conditions' => compact('user')]);

		if (!$item) {
			$item = static::create(compact('user'));
		}
		return $item->save(['score' => $item->score + 1]);
	}

	public static function decrement($user) {
		$item = static::find('first', ['conditions' => compact('user')]);

		if (!$item) {
			$item = static::create(compact('user') + ['score' => 0]);
			return $item->save();
		}
		return $item->save(['score' => $item->score > 0 ? $item->score - 1 : 0]);
	}
}

?>