<?php

use \lithium\http\Router;

Router::connect('/bot', array('plugin' => 'li3_bot', 'controller' => 'logs'));
Router::connect('/bot/{:library}/{:args}', array(
	'plugin' => 'li3_bot', 'controller' => 'logs', 'action' => 'view'
));

?>