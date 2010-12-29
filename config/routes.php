<?php

use \lithium\net\http\Router;

Router::connect('/bot', array(
	'library' => 'li3_bot', 'controller' => 'pages', 'action' => 'home'
));
Router::connect('/bot/logs', array(
	'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'index'
));
Router::connect('/bot/logs/{:channel}', array(
	'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'index'
));
Router::connect('/bot/logs/{:channel}/{:date}', array(
	'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'view'
));

?>