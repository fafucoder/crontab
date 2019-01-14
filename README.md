PHP Crontab [![Build Status](https://travis-ci.com/fafucoder/crontab.svg?branch=master)](https://travis-ci.com/fafucoder/crontab)
-----------------------

PHP crontab is the implementation of Linux crontab on PHP (essentially still requires crontab as a timing boot driver), the purpose is to facilitate crontab management.

## Get Start

### Installation
First of all you should require crontab package, is recommend way to install is through [Composer](http://getcomposer.org):
```php
$ composer require dawn/crontab
```

Then add linux crontab command to run php crontab
```
* * * * * /path/to/project && php crontab.php 1>>/dev/null 2>&1
```

The last you can write crontab job in crontab.php.

### Run a job

Crontab manager is manger job, so you can use manager to add a job or remove a job, this is an example.
```php
<?php
require dirname(__DIR__) . '/vendor/autoload.php';
use Crontab\CrontabManager;
$manager = CrontabManager::getInstance();
$manager->add('backup', array(
	'command' => 'ls -al',
	'schedule' => '* * * * *',
	'enable' => true,
	'output' => dirname(__FILE__) . '/log/output.log',
	'error' => dirname(__FILE__) . '/log/error.log',
));
$manager->run();
```

You can get a crontab and change command or change function
```php
use Crontab\CrontabManager;
$manger = CrontabManager::getInstance()->get('backup');

$manager->setMinute('*/10')
		->setHour('12')
		->setDay('*')
		->setMonth('SEP')
		->setWeek('5L')
		->setCommand('cp back.php ~/index')
		->enable()
		->setOutput('/fixtures/output.txt')
		->setErrorOutput('/fixtures/error.txt')
		->run()

//get output data
$data = $manager->getData();
$error_data = $manager->getErrorData();
```

you can delete a job you don't want anymore:
```
$manager->remove('backup');
```

you can easily to disabled, enabled a crontab to use enable options or enable,disable function.
```php
use Crontab\CrontabManager;

CrontabManager::getInstance()->enable('backup');
CrontabManager::getInstance()->disable('backup');
```

for more infomation about this project you can read this source code, if you have any problem welcom pull issue/request

## License 

This project is under MIT License. See the [LICENSE](https://github.com/fafucoder/crontab/blob/master/LICENSE) file for the full license text.


