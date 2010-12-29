<?php

use \lithium\net\http\Router;

Router::connect('/bot/logs/{:args}', array(
	'library' => 'li3_bot', 'controller' => 'logs', 'action' => 'view'
));

?>