<?php
namespace Crontab;

/**
 * Validate class is validate schedule fields is valid.
 *
 * This class is base on the mtdowling/cron-expression(https://github.com/mtdowling/cron-expression)
 * 
 * @link https://github.com/mtdowling/cron-expression/blob/master/LICENSE
 */
class Validate {
	/**
	 * The minute range.
	 * 
	 * @var array
	 */
	public $minute = array(
		'min' => 0,
		'max' => 59,
	);

	/**
	 * The hour range.
	 * 
	 * @var array
	 */
	public $hour = array(
		'min' => 0,
		'max' => 23,
	);

	/**
	 * The day range.
	 * 
	 * @var array
	 */
	public $day = array(
		'min' => 0,
		'max' => 31,
	);

	/**
	 * The month range.
	 * 
	 * @var array
	 */
	public $month => array(
		'min' => 0,
		'max' => 12,
	);

	/**
	 * The week range.
	 * 
	 * @var array
	 */
	public $week => array(
		'min' => 0,
		'max' => 6,
	);

	/**
	 * The month alias.
	 * 
	 * @var array
	 */
	public $monthAlias = array(
		1 => 'JAN', 
		2 => 'FEB',
		3 => 'MAR', 
		4 => 'APR', 
		5 => 'MAY', 
		6 => 'JUN', 
		7 => 'JUL',
        8 => 'AUG', 
        9 => 'SEP', 
        10 => 'OCT', 
        11 => 'NOV', 
        12 => 'DEC',
	);

	/**
	 * Validate minute field.
	 * 
	 * @param  string $value minute value
	 * @return boolean        
	 */
	public function minute($value) {
		return $this->validate($value, 'minute');
	}

	/**
	 * Validate hour field.
	 * 
	 * @param  string $value hour value.
	 * @return boolean        
	 */
	public function hour($value) {
		return $this->validate($value, 'hour');
	}

	/**
	 * Validate week field.
	 * 
	 * @param  string $value week value
	 * @return boolean        
	 */
	public function week($value) {
		if (!$this->validate($value, 'week')) {
			if (strpos($value, "#") !== false) {
				$chunks = explode("#", $value);

				if ($this->validate($chunks[0], 'week') && $this->validate($chunks[1])) {
					return true;
				}
			}

			if (preg_match('/^(.*)L$/', $value, $matches)) {
				return $this->validate($matches[1]);
			}

			return false;
		}

		return true;
	}

	/**
	 * Validate month field.
	 * 
	 * @param  string $value month value
	 * @return boolean        
	 */
	public function month($value) {
		if (!$this->validate($value, 'month')) {
			if (in_array($value, $this->monthAlias)) {
				return true;
			}
			return false;
		}

		return true;
	}

	/**
	 * Validate day fields.
	 * 
	 * @param string $value day value
	 * @return  boolean
	 */
	public function Day($value) {
		if (!$this->validate($value, 'day')) {
			if ($value === 'L') {
				return true;
			}

			if (strpos($value, ',') !== false && (strpos($value, "W") !== false || strpos($value, 'L') !== false)) {
				return false;
			}
			
			if (preg_match('/^(.*)W$/', $value, $matches)) {
				return $this->validate($matches[1], 'month');
			}
			return false;
		}

		return true;
	}
	
	/**
	 * Validate schedule is valid.
	 * 
	 * @param  string $value  schedule field value.
	 * @param  string $fields curren fileds
	 * @return boolean         
	 */
	protected function validate($value, $fields) {
		if ("*" === $value) {
			return true;
		}

		if (strpos($value, ',') !== false && strpos($value, '-') !== false) {
			return false;
		}

		if (strpos($value, '/') !== false) {
			list($range, $step) = explode('/', $value, 2);
			return $this->validate($range, $fields) && filter_var($step, FILTER_VALIDATE_INT);
		}

		if (strpos($value, '-') !== false) {
			if (substr_count($value, '-') > 1) {
				return false;
			}
			$chunks = explode('-', $value);
			return $this->validate($chunks[0], $fields) && $this->validate($chunks[1], $fields);
		}

		if (strpos($value, ',') !== false) {
			foreach (explode(",", $value) as $item) {
				if (!$this->validate($item, $fields)) {
					return false;
				}
			}
			return true;
		}

		if (filter_var($value, FILTER_VALIDATE_INT)) {
			$value = (int) $value;
		}

		return in_array($value, range($this->$fields['min'], $this->fields['max']), true);
	}
}