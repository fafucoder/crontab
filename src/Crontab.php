<?php
namespace Crontab;

class Crontab extends Configurable {
	/**
	 * Enabled crontab jobs.
	 * 
	 * @var array
	 */
	public $enable = true;

	/**
	 * Schedule object.
	 * 
	 * @var object 
	 */
	public $schedule;

	/**
	 * Crontab excute handler.
	 * 
	 * @var object.
	 */
	public $excuteHandle;

	/**
	 * Construct.
	 * 
	 * @param array $config crontab config
	 */
	public function __construct($config = array()) {
		if (isset($config['schedule'])) {
			$this->schedule = new Schedule($config['schedule']);
		} else {
			$this->schedule = new Schedule();
		}
		$this->excuteHandle = new excuteHandle($config);
		parend::__construct($config);
	}

	/**
	 * Enable or disable crontab.
	 * 
	 * @param  boolean $enable 
	 * @return object  return $this
	 */
	public function enable($enable = true) {
		if (is_bool($enable)) {
			$this->enable = $enable;
		}
	
		return $this;
	}

	/**
	 * Set crontab schedule.
	 * 
	 * @param string $schedule schedule
	 */
	public function setSchedule($schedule) {
		$this->schedule->setSchedule($schedule);

		return $this;
	}

	/**
	 * Set crontab schedule minute.
	 *
	 * @param string $minute minute
	 */
	public function setMinute($minute) {
		$this->schedule->setMinute($minute);

		return $this;
	}

	/**
	 * Set crontab schedule hour.
	 *
	 * @param string $hour hour
	 */
	public function setHour($hour) {
		$this->schedule->setHour($hour);

		return $this;
	}

	/**
	 * Set crontab schedule day.
	 *
	 * @param string $day day
	 */
	public function setDay($day) {
		$this->schedule->setDay($day);

		return $this;
	}

	/**
	 * Set crontab schedule month.
	 *
	 * @param string $month month
	 */
	public function setMonth($job, $month) {
		$this->schedule->setMonth($month);

		return $this;
	}

	/**
	 * Set crontab schedule week.
	 *
	 * @param string $week week
	 */
	public function setWeek($week) {
		$this->schedule->setWeek($week);

		return $this;
	}

	/**
	 * Set crontab command.
	 * 
	 * @param string|array $command command 
	 */
	public function setCommand($command) {
		$this->excuteHandle->setCommand($command);

		return $this;
	}

	/**
	 * Set crontab function.
	 * 
	 * @param mixed $function function
	 */
	public function setFunction($function) {
		$this->excuteHandle->setFunction($function);

		return $this;
	}

	/**
	 * Set crontab output file.
	 * 
	 * @param string $output output filename
	 */
	public function setOutput($output) {
		$this->excuteHandle->setOutput($output);

		return $this;
	}

	/**
	 * Set crontab error output file.
	 * 
	 * @param string $error_output error output filename
	 */
	public function setErrorOutput($error_output) {
		$this->excuteHandle->setErrorOutput($error_output);

		return $this;
	}

	/**
	 * Excute crontab from enabled crontab job.
	 * 
	 * @return void
	 */
	public function run() {
		//@TOOD
	}

	/**
	 * Get crontab output.
	 * 
	 * @return string 
	 */
	public function getOutput() {
		return $this->excuteHandle->getOutput();
	}

	/**
	 * Get crontab error output.
	 * 
	 * @return string 
	 */
	public function getErrorOutput() {
		return $this->excuteHandle->getErrorOutput();
	}
}