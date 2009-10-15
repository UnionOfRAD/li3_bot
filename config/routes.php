<?php

use \lithium\http\Router;

Router::connect('/bot', array('plugin' => 'lithium_bot', 'controller' => 'logs'));
Router::connect('/bot/{:library}/{:args}', array(
	'plugin' => 'lithium_bot', 'controller' => 'logs', 'action' => 'view'
));

?>