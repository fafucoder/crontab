<?php
namespace Crontab;

use Closure;
use Crontab\Exception\FileNotFoundException;
use Crontab\Exception\FileWriteableException;

class ExcuteHandle extends Configurable {
	/**
	 * Crontab error data output to the this filename.
	 * 
	 * @var string
	 */
	public $error;

	/**
	 * Cronab data output to the this filename.
	 * 
	 * @var string
	 */
	public $output;

	/**
	 * Schedule excute command.
	 * 
	 * @var string
	 */
	public $command;

	/**
	 * Schedule excute php function.
	 * 
	 * @var mixed
	 */
	public $function;

	/**
	 * The excuted schedule data.
	 * 
	 * @var string
	 */
	protected $data;

	/**
	 * The extuted error schedule data.
	 * 
	 * @var string
	 */
	protected $error_data;

	/**
	 * Construct.
	 * 
	 * @param array $config 
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}

	/**
	 * Set error output filename.
	 * 
	 * @param string $error error output filename
	 */
	public function setErrorOutput($error) {
		if (!file_exists($error)) {
			throw new FileNotFoundException(sprintf('Error output file not found for: %s', $error));
		}
		if (!is_writable($error)) {
			throw new FileWriteableException(sprintf('Error output file not writeable for: %s', $error));
		}

		$this->error = $error;
		return $this;
	}

	/**
	 * Get error output filename.
	 * 
	 * @return string 
	 */
	public function getErrorOutput() {
		return $this->error;
	}

	/**
	 * Set output filename.
	 * 
	 * @param string $output output filename
	 */
	public function setOutput($output) {
		if (!file_exists($output)) {
			throw new FileNotFoundException(sprintf('Output file not found for: %s', $output));
		}
		if (!is_writable($output)) {
			throw new FileWriteableException(sprintf('Output file not writeable for: %s', $output));
		}

		$this->output = $output;
		return $this;
	}

	/**
	 * Get output filename.
	 * 
	 * @return string output filename
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * Set crontab command.
	 * 
	 * @param mixed $command 
	 */
	public function setCommand($command) {
		$this->command = $command;

		return $this;
	}

	/**
	 * Get crontab command.
	 * 
	 * @return mixed 
	 */
	public function getCommand() {
		return $this->command;
	}

	/**
	 * Set crontab excute php function.
	 * 
	 * @param mixed $function 
	 */
	public function setFunction($function) {
		if (!$function instanceof Closure || !class_exists($function)) {
			throw new \Exception("Function have problem!");
		}
		$this->function = $function;

		return $this;
	}

	/**
	 * Get crontab excute php function.
	 * 
	 * @return mixed 
	 */
	public function getFunction() {
		return $this->function;
	}
}