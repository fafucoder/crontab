<?php
namespace Crontab\Tests;

use DateTime;
use Crontab\Validate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase {
	public $validate;
	public $date;

	public function setUp() {
		$this->validate = new Validate();
		$this->date = new DateTime('2018-09-26');
	}

	public function tearDown() {
		unset($this->validate, $this->date);
	}

	public function testMinute() {
		$this->date->setTime(22,45);

		$this->assertTrue($this->validate->minute($this->date, '*'));
		$this->assertTrue($this->validate->minute($this->date, 45));
		$this->assertTrue($this->validate->minute($this->date, '*/15'));
		$this->assertTrue($this->validate->minute($this->date, '15-45'));
		$this->assertTrue($this->validate->minute($this->date, '30-50/5'));

		$this->assertFalse($this->validate->minute($this->date, '60'));
		$this->assertFalse($this->validate->minute($this->date, 30));
		$this->assertFalse($this->validate->minute($this->date, '*/30'));
		$this->assertFalse($this->validate->minute($this->date, '50-55'));
		$this->assertFalse($this->validate->minute($this->date, '30-50/10'));
	}

	public function testHour() {
		$this->date->setTime(22, 10);

		$this->assertTrue($this->validate->hour($this->date, 22));
		$this->assertTrue($this->validate->hour($this->date, '*'));
		$this->assertTrue($this->validate->hour($this->date, '*/2'));
		$this->assertTrue($this->validate->hour($this->date, '20-24'));
		$this->assertTrue($this->validate->hour($this->date, '20-24/2'));

		$this->assertFalse($this->validate->hour($this->date, '60'));
		$this->assertFalse($this->validate->hour($this->date, '23'));
		$this->assertFalse($this->validate->hour($this->date, '*/3'));
		$this->assertFalse($this->validate->hour($this->date, '18-21'));
		$this->assertFalse($this->validate->hour($this->date, '18-24/3'));
	}

	public function testDay() {
		$this->date->setDate(2018, 9, 26);

		$this->assertTrue($this->validate->day($this->date, '?'));
		$this->assertTrue($this->validate->day($this->date, 26));
		$this->assertTrue($this->validate->day($this->date, '*'));
		$this->assertTrue($this->validate->day($this->date, '*/2'));
		$this->assertTrue($this->validate->day($this->date, '20-28'));
		$this->assertTrue($this->validate->day($this->date, '20-30/2'));

		$this->assertFalse($this->validate->day($this->date, 'L'));
		$this->assertFalse($this->validate->day($this->date, '31'));
		$this->assertFalse($this->validate->day($this->date, '27'));
		$this->assertFalse($this->validate->day($this->date, '*/3'));
		$this->assertFalse($this->validate->day($this->date, '18-21'));
		$this->assertFalse($this->validate->day($this->date, '21-27/3'));

		$this->date->setDate(2018, 9, 30);
		$this->assertTrue($this->validate->day($this->date, 'L'));

		$this->date->setDate(2018, 9, 28);
		$this->assertTrue($this->validate->day($this->date, '28W'));
		$this->assertFalse($this->validate->day($this->date, '23W'));
		$this->assertFalse($this->validate->day($this->date, 'L'));
	}

	public function testMonth() {
		$this->date->setDate(2018, 9, 26);

		$this->assertTrue($this->validate->month($this->date, 9));
		$this->assertTrue($this->validate->month($this->date, '*'));
		$this->assertTrue($this->validate->month($this->date, 'SEP'));
		$this->assertTrue($this->validate->month($this->date, '*/3'));
		$this->assertTrue($this->validate->month($this->date, '9-12'));
		$this->assertTrue($this->validate->month($this->date, '3-12/3'));

		$this->assertFalse($this->validate->month($this->date, '13'));
		$this->assertFalse($this->validate->month($this->date, '10'));
		$this->assertFalse($this->validate->month($this->date, '*/4'));
		$this->assertFalse($this->validate->month($this->date, '3-6'));
		$this->assertFalse($this->validate->month($this->date, '4-12/4'));
	}

	public function testWeek() {
		//3
		$this->date->setDate(2018, 9, 26);

		$this->assertTrue($this->validate->day($this->date, '?'));
		$this->assertTrue($this->validate->week($this->date, 3));
		$this->assertTrue($this->validate->week($this->date, 'WED'));
		$this->assertTrue($this->validate->week($this->date, '*'));
		$this->assertTrue($this->validate->week($this->date, '*/3'));
		$this->assertTrue($this->validate->week($this->date, '2-4'));
		$this->assertTrue($this->validate->week($this->date, '7-4'));
		$this->assertTrue($this->validate->week($this->date, '1-5/2'));

		$this->assertFalse($this->validate->week($this->date, '7'));
		$this->assertFalse($this->validate->week($this->date, '4'));
		$this->assertFalse($this->validate->week($this->date, 'MON'));
		$this->assertFalse($this->validate->week($this->date, '*/2'));
		$this->assertFalse($this->validate->week($this->date, '1-2'));
		$this->assertFalse($this->validate->week($this->date, '7-2'));
		$this->assertFalse($this->validate->week($this->date, '1-4/3'));

		//5
		$this->date->setDate(2018, 9, 28);
		$this->assertTrue($this->validate->week($this->date, '5L'));
		$this->assertTrue($this->validate->week($this->date, '5#4'));

		$this->assertFalse($this->validate->week($this->date, '4L'));
		$this->assertFalse($this->validate->week($this->date, '4#4'));
	}

	public function testIsDue() {
		$this->date->setTime(22, 30);
		$this->assertTrue($this->validate->isDue($this->date, 0, '30'));
		$this->assertTrue($this->validate->isDue($this->date, 1, '22'));
		$this->assertTrue($this->validate->isDue($this->date, 2, '26'));
		$this->assertTrue($this->validate->isDue($this->date, 3, '9'));
		$this->assertTrue($this->validate->isDue($this->date, 4, '3'));
	}
}