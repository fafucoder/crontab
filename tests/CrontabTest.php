<?php
namespace Crontab\Tests;

use Crontab\Crontab;
use Crontab\Schedule;
use PHPUnit\Framework\TestCase;
use Crontab\Tests\Fixtures\Post;

class CrontabTest extends TestCase {
	public function setUp() {
		$this->crontab = new Crontab();

	}

	public function tearDown() {
		unset($this->crontab);
	}

	public function testEnable() {
		$this->assertTrue($this->crontab->enable);

		$this->crontab->enable(false);
		$this->assertFalse($this->crontab->enable);

		$this->crontab->enable(true);
		$this->assertTrue($this->crontab->enable);
	}

	public function testSetSchedule() {
		$this->crontab->setSchedule('1 2 3 4 5');
		$schedule = $this->crontab->schedule;

		$this->assertEquals('1', $schedule->getMinute());
		$this->assertEquals('2', $schedule->getHour());
		$this->assertEquals('3', $schedule->getDay());
		$this->assertEquals('4', $schedule->getMonth());
		$this->assertEquals('5', $schedule->getWeek());
	}

	public function testSetMinute() {
		$schedule = $this->crontab->schedule;

		$this->assertEquals('0', $schedule->getMinute());
		$this->crontab->setMinute('*/2');
		$this->assertEquals('*/2', $schedule->getMinute());
		$this->crontab->setMinute('1-5/2');
		$this->assertEquals('1-5/2', $schedule->getMinute());
	}

	public function testSetHour() {
		$schedule = $this->crontab->schedule;

		$this->assertEquals('*', $schedule->getHour());
		$this->crontab->setHour('*/2');
		$this->assertEquals('*/2', $schedule->getHour());
		$this->crontab->setHour('1-5/2');
		$this->assertEquals('1-5/2', $schedule->getHour());
	}

	public function testSetDay() {
		$schedule = $this->crontab->schedule;

		$this->assertEquals('*', $schedule->getDay());
		$this->crontab->setDay('*/2');
		$this->assertEquals('*/2', $schedule->getDay());
		$this->crontab->setDay('1-5/2');
		$this->assertEquals('1-5/2', $schedule->getDay());
		$this->crontab->setDay('L');
		$this->assertEquals('L', $schedule->getDay());
	}

	public function testSetMonth() {
		$schedule = $this->crontab->schedule;

		$this->assertEquals('*', $schedule->getMonth());
		$this->crontab->setMonth('*/2');
		$this->assertEquals('*/2', $schedule->getMonth());
		$this->crontab->setMonth('9-12');
		$this->assertEquals('9-12', $schedule->getMonth());
		$this->crontab->setMonth('1-5/2');
		$this->assertEquals('1-5/2', $schedule->getMonth());
	}

	public function testSetWeek() {
		$schedule = $this->crontab->schedule;

		$this->assertEquals('*', $schedule->getWeek());
		$this->crontab->setWeek('*/2');
		$this->assertEquals('*/2', $schedule->getWeek());
		$this->crontab->setWeek('1-5');
		$this->assertEquals('1-5', $schedule->getWeek());
		$this->crontab->setWeek('1-5/2');
		$this->assertEquals('1-5/2', $schedule->getWeek());
		$this->crontab->setWeek('MON');
		$this->assertEquals('MON', $schedule->getWeek());
		$this->crontab->setWeek('5#4');
		$this->assertEquals('5#4', $schedule->getWeek());
	}

	public function testSetCommand() {
		$excute = $this->crontab->excuteHandle;

		$this->assertNull($excute->getCommand());
		$this->crontab->setCommand('ls -al');
		$this->assertEquals('ls -al', $excute->getCommand());
	}

	public function testSetFunction() {
		$excute = $this->crontab->excuteHandle;

		$this->assertNull($excute->getFunction());

		$this->crontab->setFunction(function(){
			echo "hello world";
		});
		$this->assertEquals(function(){
			echo "hello world";
		}, $excute->getFunction());

		$this->crontab->setFunction(dirname(__FILE__) . '/Fixtures/Post.php');
		$this->assertEquals(dirname(__FILE__) . '/Fixtures/Post.php', $excute->getFunction());
		
		$this->crontab->setFunction(Crontab::class);
		$this->assertEquals(Crontab::class, $excute->getFunction());
	}

	public function testSetOutput() {
		$excute = $this->crontab->excuteHandle;

		$this->assertNull($excute->getOutput());
		$this->crontab->setOutput('/fixtures/output.log');
		$this->assertEquals('/fixtures/output.log', $excute->getOutput());
	}

	public function testSetErrorOutput() {
		$excute = $this->crontab->excuteHandle;

		$this->assertNull($excute->getErrorOutput());
		$this->crontab->setErrorOutput('/fixtures/error.log');
		$this->assertEquals('/fixtures/error.log', $excute->getErrorOutput());
	}
}