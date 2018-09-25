<?php
namespace Crontab;

use DateTime;

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
	 * The week alias.
	 * 
	 * @var array
	 */
	public $weekAlias = array(
		1 => 'MON', 
		2 => 'TUE', 
		3 => 'WED', 
		4 => 'THU', 
		5 => 'FRI', 
		6 => 'SAT', 
		7 => 'SUN',
	);

    /**
     * The schedule order.
     * 
     * @var array
     */
    protected $order = array('minute', 'hour', 'day', 'month', 'week');

	/**
	 * Validate minute field.
	 * 
	 * @param  string $value minute value
	 * @return boolean        
	 */
	public function minute(DateTime $date, $value) {
		$minute = $date->format('i');

		if (strpos($value, '/') !== false) {
			return $this->isStep($minute, $value, 'minute');
		}

		if (strpos($value, '-') !== false) {
			return $this->isRange($minute, $value);
		}

		return $value === "*" || $minute == $value;
	}

	/**
	 * Validate hour field.
	 * 
	 * @param  string $value hour value.
	 * @return boolean        
	 */
	public function hour(DateTime $date, $value) {
		$hour = $date->format('H');
		if (strpos($value, '/') !== false) {
			return $this->isStep($hour, $value, 'hour');
		}

		if (strpos($value, '-') !== false) {
			return $this->isRange($hour, $value);
		}

		return $value === "*" || $hour == $value;
	}

	/**
	 * Validate day fields.
	 * 
	 * @param string $value day value
	 * @return  boolean
	 */
	public function day(DateTime $date, $value) {
		if ($value == '?') {
			return true;
		}

		$day = $date->format('d');
		if ($value == 'L') {
			return $day == $date->format('t');
		}

		if (strpos($value, 'W')) {
			$targetDay = substr($value, 0, strpos($value, 'W'));
            return $date->format('j') == $this->getNearWorkDay($date->format('Y'), $date->format('m'), $targetDay)->format('j');
		}

		if (strpos($value, '/') !== false) {
			return $this->isStep($day, $value, 'day');
		}

		if (strpos($value, '-') !== false) {
			return $this->isRange($day, $value);
		}

		return $value === "*" || $day == $value;
	}

	/**
	 * Validate month field.
	 *
	 * @param  string $value month value
	 * @return boolean        
	 */
	public function month(DateTime $date, $value) {
		if (in_array($value, $this->monthAlias)) {
			$value = array_search($value, $this->monthAlias);
		}

		$month = $date->format('m');

		if (strpos($value, '/') !== false) {
			return $this->isStep($month, $value, 'month');
		}

		if (strpos($value, '-') !== false) {
			return $this->isRange($month, $value);
		}

		return $value === "*" || $month == $value;
	}
	
	/**
	 * Validate week field.
	 * 
	 * @param  string $value week value
	 * @return boolean        
	 */
	public function week(DateTime $date, $value) {
		if ($value == '?') {
			return true;
		}

		if (in_array($value, $this->weekAlias)) {
			$value = array_search($value, $this->weekAlias);
		}

        $currentYear = $date->format('Y');
        $currentMonth = $date->format('m');
        $lastDayOfMonth = $date->format('t');

		if (strpos($value, 'L')) {
			$weekday = str_replace('7', '0', substr($value, 0, strpos($value, 'L')));
			$tdate = clone $date;
            $tdate->setDate($currentYear, $currentMonth, $lastDayOfMonth);

            while ($tdate->format('w') != $weekday) {
                $tdateClone = new DateTime();
                $tdate = $tdateClone
                    ->setTimezone($tdate->getTimezone())
                    ->setDate($currentYear, $currentMonth, --$lastDayOfMonth);
            }

            return $date->format('j') == $lastDayOfMonth;
		}

        if (strpos($value, '#')) {
            list($weekday, $nth) = explode('#', $value);

            if (!is_numeric($nth)) {
                return false;
            } else {
                $nth = (int) $nth;
            }

            // 0 and 7 are both Sunday, however 7 matches date('N') format ISO-8601
            if ($weekday == '0') {
                $weekday = 7;
            }

            $weekday = $this->convertLiterals($weekday);

            if (in_array($weekday, $this->weekAlias)) {
				$weekday = array_search($weekday, $this->weekAlias);
			}

            // Validate the hash fields
            if ($weekday < 0 || $weekday > 7) {
                return false;
            }

            if (!in_array($nth, $this->nthRange)) {
                return false;
            }

            // The current weekday must match the targeted weekday to proceed
            if ($date->format('N') != $weekday) {
                return false;
            }

            $tdate = clone $date;
            $tdate->setDate($currentYear, $currentMonth, 1);
            $dayCount = 0;
            $currentDay = 1;
            while ($currentDay < $lastDayOfMonth + 1) {
                if ($tdate->format('N') == $weekday) {
                    if (++$dayCount >= $nth) {
                        break;
                    }
                }
                $tdate->setDate($currentYear, $currentMonth, ++$currentDay);
            }

            return $date->format('j') == $currentDay;
        }

        $format = in_array(7, str_split($value)) ? 'N' : 'w';
        $week = $date->format($format);

		if (strpos($value, '/') !== false) {
			return $this->isStep($week, $value, 'week');
		}

		if (strpos($value, '-') !== false) {
			// Handle day of the week values
            $parts = explode('-', $value);
            if ($parts[0] == '7') {
                $parts[0] = '0';
            } elseif ($parts[1] == '0') {
                $parts[1] = '7';
            }
            $value = implode('-', $parts);

			return $this->isRange($week, $value);
		}

		return $value === "*" || $week == $value;
	}

	/**
	 * Is due.
	 * 
	 * @param  object  $date  DateTime
	 * @param  string  $value 
	 * @return boolean 
	 */
	public function isDue(DateTime $date, $position, $value) {
		if ($value === '*' || $value === null) {
			return true;
		}

		return $this->order[$position]($date, $value);
	}

	/**
	 * Is range.
	 * 
	 * @param  string  $dateValue current date value
	 * @param  string  $value     schedule value
	 * @return boolean            
	 */
	public function isRange($dateValue, $value) {
		$range = array_map('trim', explode('-', $value, 2));

		return $dateValue >= $range[0] && $dateValue <= $range[1];
	}

	/**
	 * Is step.
	 * 
	 * @param  string  $dateValue current date value
	 * @param  string  $value     schedule value
	 * @param  string  $type      field type
	 * @return boolean            
	 */
	public function isStep($dateValue, $value, $type) {
		$chunks = array_map('trim', explode('/', $value, 2));
		$range = $chunks[0];
        $step = isset($chunks[1]) ? $chunks[1] : 0;

        if (is_null($step) || 0 == $step) {
        	return false;
        }

        if ("*" == $range) {
        	$range = $this->$type['min'] . '-' . $this->$type['max'];
        }
        list($start, $end) = explode("-", $range, 2);

        if ($start > $end) {
        	return false;
        }

        $interval = (int)$end - (int)$start +1;
        if ($step > $interval) {
        	return false;
        }

        $range = range($start, $end, $step);

        return in_array($dateValue, $range);
	}

	/**
	 * Get near work day.
	 * 
	 * @param  int $year  
	 * @param  int $month 
	 * @param  int $day   
	 * @return DateTime
	 */
	public function getNearWorkDay($year, $month, $day) {
        $tday = str_pad($day, 2, '0', STR_PAD_LEFT);
        $target = DateTime::createFromFormat('Y-m-d', "$year-$month-$tday");
        $currentWeekday = (int) $target->format('N');

        if ($currentWeekday < 6) {
            return $target;
        }

        $lastDayOfMonth = $target->format('t');

        foreach (array(-1, 1, -2, 2) as $i) {
            $adjusted = $targetDay + $i;
            if ($adjusted > 0 && $adjusted <= $lastDayOfMonth) {
                $target->setDate($currentYear, $currentMonth, $adjusted);
                if ($target->format('N') < 6 && $target->format('m') == $currentMonth) {
                    return $target;
                }
            }
        }
	}
}