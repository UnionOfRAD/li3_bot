<?php

use lithium\core\Environment;
use lithium\core\Libraries;

$files = array(
	Libraries::get(true, 'path') . '/config/li3_bot.ini',
	__DIR__ . '/li3_bot.ini',
);
foreach ($files as $file) {
	if (file_exists($file)) {
		$config = parse_ini_file($file);
		break;
	}
}
Environment::set(true, array(
	'bot' => $config
));


?>