<?php
namespace Crontab;

use Crontab\Exception\CrontabNotFoundException;

class CrontabManager {
	/**
	 * Registered jobs.
	 * 
	 * @var array
	 */
	public $registered = array();

	/**
	 * Singleton object.
	 * 
	 * @var object
	 */
	public static $instance;

	/**
	 * Singleton functions.
	 * 
	 * @return object 
	 */
	public static function getInstance() {
		if (isset(self::$instance) || !self::$instance instanceof self) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add a job.
	 * 
	 * @param string $job    job name
	 * @param array  $config crontab config
	 * @return object return $this
	 */
	public function add($job, $config = array()) {
		if (is_array($job)) {
			foreach ($job as $j => $conf) {
				$this->add($j, $conf);
			}
		} else {
			if (!array_key_exists($job, $this->registered)) {
				$crontab = new Crontab($config);
				$this->registered[$job] = $crontab;
			}
		}
	}

	/**
	 * Remove a job from exists crontab jobs.
	 * 
	 * @param  string $job job name
	 * @return void
	 */
	public function remove($job) {
		if ($this->has($job)) {
			unset($this->registered[$job]);
		}
	}

	/**
	 * Return if exists crontab job.
	 * 
	 * @param  string  $job job name
	 * @return boolean      
	 */
	public function has($job) {
		return isset($this->registered[$job]);
	}

	/**
	 * Clear all crontab.
	 * 
	 * @return void 
	 */
	public function clear() {
		$this->registered = array();
	}


	/**
	 * Get crontab.
	 * 
	 * @param  string $job job name
	 * 
	 * @return object  return crontab
	 */
	public function get($job) {
		if ($this->has($job)) {
			return $this->registered[$job];
		}

		throw new CrontabNotFoundException(sprintf('Crontab not found for: %s', $job));
	}

	/**
	 * Enable a crontab job from exists crontab jobs.
	 * 
	 * @param string $job job name
	 * @return boolean
	 */
	public function enable($job) {
		if ($this->has($job)) {
			$crontab = $this->registered[$job];
			$crontab->enable(true);

			return true;
		}

		return false;
	}

	/**
	 * Disable a crontab job from enabed crontab jobs.
	 * 
	 * @param  string $job job name
	 * @return object  return $this
	 */
	public function disable($job) {
		if ($this->has($job)) {
			$crontab = $this->get($job);
			$crontab->enable(false);

			return true;
		}

		return false;
	}

	/**
	 * Excute crontab from enabled crontab job.
	 * 
	 * @return void
	 */
	public function run() {
		foreach ($this->registered as $crontab) {
			if ($crontab->enable) {
				$crontab->run();
			}
		}
	}
}