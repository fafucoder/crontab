###配置crontab###
~~~php
<?php
use Crontab\Crontab;

$crontab = new Crontab('秒杀', array(
	'command' => 'ls -al',
	'output' => dirname(__FIlE__) . '/output.php',
	'error' => dirname(__FILE__) . '/output.log',
	'schedule' => '0 * * * *',
	'enabled' => true,
	'function' => function() {
		$class = new Class();
	}
))->run();
~~~

###Crontab方法###
~~~php
<?php
$crontab
	->setCommand('ls -al')
	->setOutput(__DIR__ . '/output.php')
	->setError(__DIR__ . '/output.log')
	->setFunction(function() {
		echo "hello world";
	})
	->setSchedule("* * * * *")
	->addJob('活动')

~~~

###移除 Crontab###
~~~php
<?php
$crontab->removeJob('秒杀');
~~~

###添加Crontab###
~~~php
<?php
$crontab->addJob('秒杀', array(
	'command' => 'ls -al',
	'output' => dirname(__FIlE__) . '/output.php',
	'error' => dirname(__FILE__) . '/output.log',
	'schedule' => '0 * * * *',
	'enabled' => true,
	'function' => function() {
		$class = new Class();
	}
));
~~~

###Disable Crontab###
~~~php
<?php
$crontab->disable('秒杀');
~~~

###Enable Crontab###
~~~php
<?php
$crontab->enable('秒杀');
~~~

###Run crontab###
必须是enable才可以执行crontab,如果是disable则不执行crontab
~~~php
<?php
$crontab->enable('秒杀')->run('秒杀');
~~~

###Get the output for crontab###
~~~php
<?php
$output = $crontab->getOutput('秒杀');
~~~


###Clear crontab###
~~~php
<?php
$crontab->clear();
~~~


###关于设置schedule可以有其他的方法###
~~~php
<?php
$crontab
	->setMinute('秒杀', '*')
	->setHour('秒杀', '*')
	->setDay('秒杀', '23')
	->setMonth('秒杀', '1,2,3')
	->Week('秒杀', '1')
	->setCommand('秒杀', 'ls -al');
~~~