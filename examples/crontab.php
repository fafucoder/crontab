<?php
require dirname(__DIR__) .'/vendor/autoload.php';

use Crontab\CrontabManager;

$manager = CrontabManager::getInstance();

$manager->add('backup', array(
	'command' => 'ls /home/dawn',
	'schedule' => '* * * * *',
	'enable' => false,
	'output' => dirname(__FILE__) . '/log/output.log',
	'error' => dirname(__FILE__) . '/log/error.log',
));

$manager->run();