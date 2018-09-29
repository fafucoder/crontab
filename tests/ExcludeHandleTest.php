<?php
namespace Crontab\Tests;

use PHPUnit\Framework\TestCase;
use Crontab\ExcuteHandle;
use Crontab\Tests\Fixtures\Post;

class ExcludeHandleTest extends TestCase {
	public function setUp() {
		$this->excute = new ExcuteHandle();
	}

	public function tearDown() {
		unset($this->excute);
	}

	public function testSetErrorOutput() {
		$this->assertNull($this->excute->getErrorOutput());

		$this->excute->setErrorOutput('/fixtures/error.txt');
		$this->assertEquals('/fixtures/error.txt', $this->excute->getErrorOutput());
	}

	public function testSetOutput() {
		$this->assertNull($this->excute->getOutput());

		$this->excute->setOutput('/fixtures/output.txt');
		$this->assertEquals('/fixtures/output.txt', $this->excute->getOutput());
	}

	public function testSetCommand() {
		$this->assertNull($this->excute->getCommand());

		$this->excute->setCommand('echo ok');
		$this->assertEquals('echo ok', $this->excute->getCommand());
	}

	public function testSetFunction() {
		$this->assertNull($this->excute->getFunction());

		$this->excute->setFunction(function(){
			echo "hello world";
		});
		$this->assertEquals(function(){
			echo "hello world";
		}, $this->excute->getFunction());

		$this->excute->setFunction(dirname(__FILE__) . '/Fixtures/Post.php');
		$this->assertEquals(dirname(__FILE__) . '/Fixtures/Post.php', $this->excute->getFunction());
		
		$this->excute->setFunction(ExcuteHandle::class);
		$this->assertEquals(ExcuteHandle::class, $this->excute->getFunction());
	}

	public function testExcuteCommand() {
		$this->assertNull($this->excute->getCommand());

		$this->excute->excuteCommand('echo ok');

		$this->assertEquals('ok' . PHP_EOL, $this->excute->getData());
	}

	public function testExcuteFunction() {
		$this->assertNull($this->excute->getFunction());

		$this->excute->setFunction(function(){
			return "hello world";
		});
		$this->assertEquals(function(){
			return "hello world";
		}, $this->excute->getFunction());
		$this->excute->excuteFunction();
		$this->assertEquals('hello world', $this->excute->getData());

		$this->excute->setFunction(dirname(__FILE__) . '/Fixtures/index.php');
		$this->excute->excuteFunction();
		$this->assertEquals('index content', $this->excute->getData());
	}
}