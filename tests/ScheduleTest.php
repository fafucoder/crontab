<?php
namespace Crontab\Tests;

use DateTime;
use Crontab\Schedule;
use PHPUnit\Framework\TestCase;
use Crontab\Exception\ScheduleException;

class ScheduleTest extends TestCase {
	public function testSetAndGetMinute() {
		$schedule = new Schedule('*/2 * * * *');
		$this->assertSame('*/2', $schedule->getMinute());

		$schedule->setMinute('*');
		$this->assertEquals('*', $schedule->getMinute());
	}

	public function testSetAndGetHour() {
		$schedule = new Schedule('* 22 * * *');
		$this->assertSame('22', $schedule->getHour());

		$schedule->setHour('22-56');
		$this->assertEquals('22-56', $schedule->getHour());

		$schedule->setHour('22-44/2');
		$this->assertEquals('22-44/2', $schedule->getHour());

		$schedule->setHour('*/2');
		$this->assertEquals('*/2', $schedule->getHour());
	}

	public function testSetAndGetDay() {
		$schedule = new Schedule('* * 30 * *');
		$this->assertSame('30', $schedule->getDay());

		$schedule->setDay('12-23');
		$this->assertEquals('12-23', $schedule->getDay());

		$schedule->setDay('10-20/3');
		$this->assertEquals('10-20/3', $schedule->getDay());

		$schedule->setDay('*/2');
		$this->assertEquals('*/2', $schedule->getDay());

		$schedule->setDay('L');
		$this->assertEquals('L', $schedule->getDay());

		$schedule->setDay('15W');
		$this->assertEquals('15W', $schedule->getDay());
	}

	public function testSetAndGetMonth() {
		$schedule = new Schedule('* * * 10 *');
		$this->assertSame('10', $schedule->getMonth());

		$schedule->setMonth('4-12');
		$this->assertEquals('4-12', $schedule->getMonth());

		$schedule->setMonth('2-11/2');
		$this->assertEquals('2-11/2', $schedule->getMonth());

		$schedule->setMonth('*/2');
		$this->assertEquals('*/2', $schedule->getMonth());

		$schedule->setMonth('MAY');
		$this->assertEquals('MAY', $schedule->getMonth());
	}

	public function testSetAndGetWeek() {
		$schedule = new Schedule('* * * * 4');
		$this->assertSame('4', $schedule->getWeek());

		$schedule->setWeek('MON');
		$this->assertEquals('MON', $schedule->getweek());

		$schedule->setWeek('1-6/3');
		$this->assertEquals('1-6/3', $schedule->getWeek());

		$schedule->setWeek('*/2');
		$this->assertEquals('*/2', $schedule->getWeek());

		$schedule->setDay('L');
		$this->assertEquals('L', $schedule->getDay());

		$schedule->setDay('?');
		$this->assertEquals('?', $schedule->getDay());

		$schedule->setDay('5#4');
		$this->assertEquals('5#4', $schedule->getDay());
	}

	/**
     * @dataProvider scheduleProvider
     */
	public function testParseSchedule($schedule, $minute, $hour, $day, $month, $week) {
		$schedule = new Schedule($schedule);

		$this->assertEquals($minute, $schedule->getMinute());
		$this->assertEquals($hour, $schedule->getHour());
		$this->assertEquals($day, $schedule->getDay());
		$this->assertEquals($month, $schedule->getMonth());
		$this->assertEquals($week, $schedule->getWeek());
	}

	public function scheduleProvider() {
		return [
			['@daily', '0', '0', '*', '*', '*'],
			['@yearly', '0', '0', '1', '1', '*'],
			['@annually', '0', '0', '1', '1', '*'],
			['@weekly', '0', '0', '*', '*', '0'],
			['@monthly', '0', '0', '1', '*', '*'],
			['@midnight', '0', '0', '*', '*', '*'],
			['@hourly', '0', '*', '*', '*', '*'],
			['*/2 23-45 23 12-23/2 *', '*/2', '23-45', '23', '12-23/2', '*'],
		];
	}

	/**
     * @dataProvider validateProvider
     */
	public function testValidateSchedule($date, $schedule) {
		$schedule = new Schedule($schedule);
		
		$this->assertTrue($schedule->validateSchedule($date));
	}

	public function validateProvider() {
		return [
			[new DateTime('2018-09-26 00:00:00'), '@daily'],
			[new DateTime('2018-01-01 00:00:00'), '@yearly'],
			[new DateTime('2018-09-26 00:00:00'), '@hourly'],
			[new DateTime('2018-09-26 00:00:00'), '@midnight'],
			[new DateTime('2018-09-01 00:00:00'), '@monthly'],
			[new DateTime('2018-10-01 00:00:00'), '@monthly'],
			[new DateTime('2018-01-01 00:00:00'), '@annually'],

			[new DateTime('2018-09-26 00:30:00'), '* * * * *'],
			[new DateTime('2018-09-26 00:30:00'), '*/2 * * * *'],
			[new DateTime('2018-09-26 00:30:00'), '20-40 * * * *'],
			[new DateTime('2018-09-26 00:30:00'), '20-40/2 * * * *'],

			[new DateTime('2018-09-26 18:00:00'), '* * * * *'],
			[new DateTime('2018-09-26 10:00:00'), '* */2 * * *'],
			[new DateTime('2018-09-26 12:00:00'), '* 10-23/2 * * *'],
			[new DateTime('2018-09-26 15:00:00'), '* 12-20 * * *'],

			[new DateTime('2018-09-26 00:00:00'), '* * 10-30 * *'],
			[new DateTime('2018-09-26 00:00:00'), '* * 10-28/2 * *'],
			[new DateTime('2018-09-30 00:00:00'), '* * L * *'],
			[new DateTime('2018-09-28 00:00:00'), '* * 28W * *'],

			[new DateTime('2018-09-26 00:00:00'), '* * * 9 *'],
			[new DateTime('2018-09-26 00:00:00'), '* * * SEP *'],
			[new DateTime('2018-09-26 00:00:00'), '* * * */3 *'],
			[new DateTime('2018-09-26 00:00:00'), '* * * 9-12 *'],
			[new DateTime('2018-09-26 00:00:00'), '* * * 3-12/3 *'],

			[new DateTime('2018-09-26 00:00:00'), '* * * * WED'],
			[new DateTime('2018-09-26 00:00:00'), '* * * * 3'],
			[new DateTime('2018-09-26 00:00:00'), '* * * * ?'],
			[new DateTime('2018-09-26 00:00:00'), '* * * * */3'],
			[new DateTime('2018-09-26 00:00:00'), '* * * * 2-4'],
			[new DateTime('2018-09-28 00:00:00'), '* * * * 5L'],
			[new DateTime('2018-09-28 00:00:00'), '* * * * 5#4'],

			[new DateTime('2018-09-28 00:00:00'), '0 0 * 3-12/3 5#4'],
			[new DateTime('2018-09-28 00:30:00'), '20-40/2 * * 9-12 5L'],
			[new DateTime('2018-09-26 10:30:00'), '30 */2 20-30/2 * */3'],
			[new DateTime('2018-09-26 10:45:00'), '45 * * * ?'],
			[new DateTime('2018-09-26 10:45:00'), '30-50/3 10-12 * SEP *'],
		];
	}
}