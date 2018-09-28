<?php
namespace Crontab\Tests;

use Crontab\Crontab;
use Crontab\Schedule;
use PHPUnit\Framework\TestCase;

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

	}

	public function testSetDay() {

	}

	public function testSetMonth() {

	}

	public function testSetWeek() {

	}

	public function testSetCommand() {

	}

	public function testSetFunction() {

	}

	public function testSetOutput() {

	}

	public function testSetErrorOutput() {

	}

	public function testGetData() {

	}

	public function testGetErrorDate() {
		
	}
}