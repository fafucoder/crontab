<?php
namespace Crontab\Schedule;

class Schedule {
	/**
	 * Schedule minute.
	 * 
	 * @var string
	 */
	protected $minute = "*";

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
        $this->week = $week;

        return $this;
    }
}