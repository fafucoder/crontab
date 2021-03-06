<?php
namespace Crontab;

use Closure;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\PhpExecutableFinder;

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
	protected $data = array();

	/**
	 * The extuted error schedule data.
	 * 
	 * @var string
	 */
	protected $error_data = array();

	/**
	 * Set error output filename.
	 * 
	 * @param string $error error output filename
	 */
	public function setErrorOutput($error) {
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
		if ($function instanceof Closure || class_exists($function) || file_exists($function)) {
			$this->function = $function;

			return $this;
		}
		throw new \Exception("Function must is function or class or file and file exist!");
	}

	/**
	 * Get crontab excute php function.
	 * 
	 * @return mixed 
	 */
	public function getFunction() {
		return $this->function;
	}

	/**
	 * Excute command.
	 * 
	 * @param  mixed $command 
	 * @return void
	 */
	public function excuteCommand($command) {
		$process = new Process($command);
		$process->run();

		$this->data = $process->getOutput();
		$this->error_data = $process->getErrorOutput();
	}

	/**
	 * Excute function.
	 * 
	 * @return mixed 
	 */
	public function excuteFunction() {
		if ($this->function instanceof Closure) {
			$this->data = call_user_func($this->function);
		} elseif (file_exists($this->function)) {
			$path = $this->phpFinder();
			$command = array($path, $this->function);
			$this->excuteCommand($command);
		} elseif (class_exists($this->function)) {
			//@TODO may be need further fixed
			$class = $this->function;
			return new $class();
		}
	}

	/**
	 * Excute function or command.
	 * 
	 * @return void
	 */
	public function excute() {
		if ($this->function) {
			$this->excuteFunction();
		}

		if ($this->command) {
			$this->excuteCommand($this->command);
		}

		if ($this->data && $this->output) {
			$this->renderOutput($this->data, $this->output);
		}

		if ($this->error_data && $this->error) {
			$this->renderOutput($this->error_data, $this->error);
		}
	}

	/**
	 * Get data.
	 * 
	 * @return string 
	 */
	public function getData() {
		return $this->data;
	}
	/**
	 * Get error data.
	 * 
	 * @return string 
	 */
	public function getErrorData() {
		return $this->error_data;
	}

	/**
	 * Finding the Executable PHP Binary, '/usr/local/bin/php'.
	 * 
	 * @return string php path
	 */
	protected function phpFinder() {
		$phpBinaryFinder = new PhpExecutableFinder();
		$phpBinaryPath = $phpBinaryFinder->find();

		return $phpBinaryPath;
	}

	/**
	 * Render data to the output file.
	 * 
	 * @param  string $data output data
	 * @param  string $file output file
	 * @return void
	 */
	protected function renderOutput($data, $file) {
		$fp = fopen($file, 'a');

		if(flock($fp, LOCK_EX)) {
			fwrite($fp, $data . PHP_EOL);
		}

		flock($fp,LOCK_UN);
		fclose($fp);
	}
}