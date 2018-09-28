<?php
namespace Crontab\Tests;

use Crontab\CrontabManager;
use PHPUnit\Framework\TestCase;

class CrontabManagerTest extends TestCase {
	public function setUp() {
		$this->manager = CrontabManager::getInstance();
	}

	public function tearDown() {
		$this->manager->registered = array();
	}

	public function testAdd() {
		$this->assertEquals(array(), $this->manager->registered);

		$this->manager->add('backup', array(
			'command' => 'ls -al',
			'output' => dirname(__FIlE__) . '/output.php',
			'error' => dirname(__FILE__) . '/output.log',
			'schedule' => '0 * * * *',
			'enabled' => true,
		));

		$this->assertArrayHasKey('backup', $this->registered);
		$this->assertInstanceOf(Crontab::class, $this->registered['backup']);
	}	

	// public function testRemove() {

	// }

	// public function testHas() {

	// }

	// public function testClear() {

	// }

	// public function testGet() {

	// }

	// public function testEnable() {

	// }

	// public function testDisable() {

	// }

	// public function testRun() {
		
	// }
}