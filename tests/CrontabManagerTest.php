<?php
namespace Crontab\Tests;

use Crontab\Crontab;
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

		$this->assertArrayHasKey('backup', $this->manager->registered);
		$this->assertInstanceOf(Crontab::class, $this->manager->registered['backup']);
	}	

	public function testAddWithArray() {
		$this->assertEquals(array(), $this->manager->registered);

		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$this->assertArrayHasKey('backup', $this->manager->registered);
		$this->assertArrayHasKey('remove_cache', $this->manager->registered);
		$this->assertEquals(new Crontab(array('command' => 'rm -rf *', 'schedule' => '@daily')), 
			$this->manager->registered['remove_cache']
		);
	}

	public function testRemove() {
		$this->assertEquals(array(), $this->manager->registered);

		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$this->assertArrayHasKey('backup', $this->manager->registered);
		$this->assertArrayHasKey('remove_cache', $this->manager->registered);

		$this->manager->remove('backup');
		$this->assertArrayNotHasKey('backup', $this->manager->registered);
		$this->assertArrayHasKey('remove_cache', $this->manager->registered);

		$this->manager->remove('remove_cache');
		$this->assertArrayNotHasKey('backup', $this->manager->registered);
		$this->assertArrayNotHasKey('remove_cache', $this->manager->registered);
	}

	public function testHas() {
		$this->assertFalse($this->manager->has('backup'));
		
		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$this->assertTrue($this->manager->has('backup'));
	}

	public function testClear() {
		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$this->assertTrue($this->manager->has('backup'));
		$this->assertTrue($this->manager->has('remove_cache'));

		$this->manager->clear();

		$this->assertFalse($this->manager->has('backup'));
		$this->assertFalse($this->manager->has('remove_cache'));

		$this->assertEquals(array(), $this->manager->registered);
	}

	public function testGet() {
		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$this->assertInstanceOf(Crontab::class, $this->manager->get('backup'));

		$this->assertEquals(new Crontab(array(
			'command' => 'ls -al',
			'schedule' => '* * * * *',
		)), $this->manager->get('backup'));

	}

	/**
	 * @expectedException \Crontab\Exception\CrontabNotFoundException
	 * @expectedExceptionMessage Crontab not found for: crontab
	 */
	public function testGetWithException() {
		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$this->manager->get('crontab');
	}

	public function testEnable() {
		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
				'enable' => false,
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$backup = $this->manager->get('backup');
		$this->assertEquals(false, $backup->enable);

		$this->manager->enable('backup');
		$this->assertEquals(true, $backup->enable);
	}

	public function testDisable() {
		$this->manager->add(array(
			'backup' => array(
				'command' => 'ls -al',
				'schedule' => '* * * * *',
			),
			'remove_cache' => array(
				'command' => 'rm -rf *',
				'schedule' => '@daily',
			),
		));

		$backup = $this->manager->get('backup');
		$this->assertEquals(true, $backup->enable);

		$this->manager->disable('backup');
		$this->assertEquals(false, $backup->enable);
	}
}