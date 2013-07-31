<?php
namespace Noodle\Options;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NoodleOptions implements ServiceLocatorAwareInterface{

	private $configPath = 'config/autoload/noodle.local.php';

	public function getOptions()
	{
		$currentConfig = $this->getServiceLocator()->get('config');

		return $currentConfig['noodle'];
	}

	public function getOption($index)
	{
		$currentConfig = $this->getServiceLocator()->get('config');

		$parts = explode('.', $index);

		$optionArrayPath = $currentConfig['noodle'];
		foreach($parts as $part){
			if(!isset($optionArrayPath[$part])){
				return false;
			}
			$optionArrayPath = $optionArrayPath[$part];
		}

		return $optionArrayPath;
	}

	public function hasOption($index)
	{
		return $this->getOption($index);
	}

	public function setOption($index, $value)
	{
		$options = array();
		if(is_file($this->configPath)) {
			$options = include $this->configPath;
			$options = $options['noodle'];
		}

		// Create config
		$config = new \Zend\Config\Config(array('noodle' => $options), true);

		$parts = explode('.', $index);

		if(!isset($config->noodle)){
			$config->noodle = array();
		}

		$currentConfigObject = $config->noodle;
		$optionArrayPath = $options;
		foreach($parts as $index => $part){
			if($index == count($parts)-1){
				$currentConfigObject->$part = $value;
			} else {
				if(!isset($optionArrayPath[$part])){
					$currentConfigObject->$part = array();
				}
			}

			$optionArrayPath = $optionArrayPath[$part];
			$currentConfigObject = $currentConfigObject->$part;
		}

		//$config = $config->merge($this->getServiceLocator()->get('config'));

		$writer = new \Zend\Config\Writer\PhpArray();
		$writer->toFile($this->configPath, $config);
	}

	/**
	 * Interface methods
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

}
