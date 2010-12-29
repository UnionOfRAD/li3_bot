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
Router::connect('/bot/logs/{:channel}/{:date:[0-9]{4}-[0-9]{2}-[0-9]{2}}', array(
	'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'view'
));
Router::connect('/bot/logs/{:channel}/search', array(
	'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'search'
));
Router::connect('/bot/tells', array(
	'library' => 'li3_bot', 'controller' => 'tells', 'action' => 'index'
));

?>