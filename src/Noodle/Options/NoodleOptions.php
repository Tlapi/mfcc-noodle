<?php
namespace Noodle\Options;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NoodleOptions implements ServiceLocatorAwareInterface{

	private $configPath = 'config/autoload/noodle.local.php';
	
	private $loadedConfig;

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
		if(!$this->loadedConfig){
			if(is_file($this->configPath)) {
				$options = include $this->configPath;
				$options = $options['noodle'];
			}
	
			// Create config
			$this->loadedConfig = new \Zend\Config\Config(array('noodle' => $options), true);
		}

		$parts = explode('.', $index);

		if(!isset($this->loadedConfig->noodle)){
			$this->loadedConfig->noodle = array();
		}

		if(count($parts)==1){
			$key = $parts[0];
			$this->loadedConfig->noodle->$key = $value;
		} else {
			$key1 = $parts[0];
			$key2 = $parts[1];
			if(!isset($this->loadedConfig->noodle->$key1)){
				$this->loadedConfig->noodle->$key1 = array();
			}
			$this->loadedConfig->noodle->$key1->$key2 = $value;
		}
		
		$writer = new \Zend\Config\Writer\PhpArray();
		$writer->toFile($this->configPath, $this->loadedConfig);
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
