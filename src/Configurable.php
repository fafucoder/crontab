<?php
namespace Crontab;

abstract class Configurable {

	/**
	 * Construct.
	 * 
	 * @param array $config
	 */
	public function __construct(array $config = array()) {
		static::configure($this, $config);
	}

	/**
	 * Configure an object with the init property value.
	 * 
	 * @param  object $object     the object to be configured
	 * @param  array $proterties the property init value
	 * @return void           
	 */
	public static function configure($object, $proterties) {
		foreach ($proterties as $key => $value) {
			$object->$key = $value;
		}
	}
}