<?php
namespace Noodle\Entity;

class Base
{

	public function getListValue($key)
	{
		//return $this->$key;
		//$this->getProperties();
		//var_dump(get_class_methods($this->select));
		//if($this->$key instanceof );

		$reflClass = new \ReflectionClass(get_class($this));
		//var_dump(get_class($this));exit();

		var_dump($reflClass->getProperties());

		exit();
		return 'test';
	}

}
