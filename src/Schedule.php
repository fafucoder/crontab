<?php
namespace Crontab;

use Crontab\Exception\ScheduleException;
use Crontab\Validate;
use DateTime;

class Schedule {
    /**
     * Default crontab schedule.
     * 
     * @var array
     */
    protected $schedules = array(
        '@yearly' => '0 0 1 1 *',
        '@annually' => '0 0 1 1 *',
        '@monthly' => '0 0 1 * *',
        '@weekly' => '0 0 * * 0',
        '@daily' => '0 0 * * *',
        '@midnight' => '0 0 * * *',
        '@hourly' => '0 * * * *',
    );

    /**
     * Preg schedule.
     * 
     * @var array
     */
    static protected $regex = array(
        'minute' => '/[\*,\/\-0-9]+/',
        'hour' => '/[\*,\/\-0-9]+/',
        'month' => '/[\*,\/\-0-9A-Z]+/',
        'week' => '/[\*,\/\-\?L#0-9A-Z]+/',
        'day' => '/[\*,\/\-\?#LWC0-9]+/',
    );

	/**
	 * Schedule minute.
	 * 
	 * @var string
	 */
	protected $minute = "0";

	/**
	 * Schedule hour.
	 * 
	 * @var string
	 */
	protected $hour = "*";

	/**
	 * Schedule day.
	 * 
	 * @var string
	 */
	protected $day = "*";

	/**
	 * Schedule month.
	 * 
	 * @var string
	 */
	protected $month = "*";

	/**
	 * Schedule week.
	 * 
	 * @var string
	 */
	protected $week = "*";

    /**
     * Validate instance.
     * 
     * @var object
     */
    protected $validate;

    /**
     * The schedule order.
     * 
     * @var array
     */
    protected $order = array('minute', 'hour', 'day', 'month', 'week');

	/**
	 * Construct.
	 * 
	 * @param string $schedule schedule string
	 */
	public function __construct($schedule = '') {
        if ($schedule) {
            $this->parseSchedule($schedule);
        }

        $this->validate = new Validate();
	}

    /**
     * Get Schedule minute.
     * 
     * @return string
     */
    public function getMinute() {
        return $this->minute;
    }

    /**
     * Set schedule minute.
     * 
     * @param string $minute
     *
     * @return self
     */
    public function setMinute($minute) {
        if (!preg_match(self::$regex['minute'], $minute)) {
            throw new ScheduleException(sprintf('Minute set is incorrect for: %s', $minute));
        }

        $this->minute = $minute;

        return $this;
    }

    /**
     * Get schudule hour.
     * 
     * @return string
     */
    public function getHour() {
        return $this->hour;
    }

    /**
     * Set schedule hour.
     * 
     * @param string $hour
     *
     * @return self
     */
    public function setHour($hour) {
        if (!preg_match(self::$regex['hour'], $hour)) {
            throw new ScheduleException(sprintf('Hour set is incorrect for: %s', $hour));
        }
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get schedule day.
     * 
     * @return string
     */
    public function getDay() {
        return $this->day;
    }

    /**
     * Set schedule day.
     * 
     * @param string $day
     *
     * @return self
     */
    public function setDay($day) {
        if (!preg_match(self::$regex['day'], $day)) {
            throw new ScheduleException(sprintf('Day set is incorrect for: %s', $day));
        }
        $this->day = $day;

        return $this;
    }

    /**
     * Get schedule month.
     * 
     * @return string
     */
    public function getMonth() {
        return $this->month;
    }

    /**
     * Set schedule month.
     * 
     * @param string $month
     *
     * @return self
     */
    public function setMonth($month) {
        if (!preg_match(self::$regex['month'], $month)) {
            throw new ScheduleException(sprintf('Month set is incorrect for: %s', $month));
        }
        $this->month = $month;

        return $this;
    }

    /**
     * Get schedule week.
     * 
     * @return string
     */
    public function getWeek() {
        return $this->week;
    }

    /**
     * Set schedule week.
     * 
     * @param string $week
     *
     * @return self
     */
    public function setWeek($week) {
        if (!preg_match(self::$regex['week'], $week)) {
            throw new ScheduleException(sprintf('Week set is incorrect for: %s', $minute));
        }
        $this->week = $week;

        return $this;
    }

    /**
     * Parse schedule.
     * 
     * @param  string $schedule schedule
     * @return void           
     */
    public function parseSchedule($schedule) {
        if (array_key_exists($schedule, $this->schedules)) {
            $schedule = $this->schedules[$schedule];
        }

        $schedule = preg_split('/\s/', $schedule, -1, PREG_SPLIT_NO_EMPTY);

        if (count($schedule) < 5) {
            throw new ScheduleException('crontab schedule expression is not valid');
        }

        foreach ($schedule as $position => $value) {
            $key = $this->order[$position];
            $this->$key = $value;
        }
    }

    /**
     * Check schedule.
     * 
     * @return boolean
     */
    public function isRun() {
        $date = DateTime::createFromFormat('Y-m-d H:i:s');
        try {
            return $this->validateSchedule($date);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validate schedule.
     * 
     * @param  DateTime $date 
     * @return boolean
     */
    public function validateSchedule(DateTime $date) {
        $currentDate = clone $date;

        //set current time.
        $currentDate->setTime($currentDate->format('H'), $currentDate->format('i'), 0);

        foreach ($this->order as $position => $field) {
            $value= $this->$field;

            if ($this->validate->isDue($currentDate, $position, $value) === false) {
                return false;
            }
        }

        return true;
    }
}