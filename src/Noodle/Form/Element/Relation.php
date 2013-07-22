<?php

namespace Noodle\Form\Element;

use Zend\Form\Element\Select;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Relation extends Select implements ServiceLocatorAwareInterface
{

	protected $serviceLocator;

	public function init()
	{
		// Here, we have $this->serviceLocator !!
	}

	public function prepare()
	{
		//var_dump($this->getOptions());
		$targetEntity = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->getRepository($this->getOption('targetEntity'));
		$relCol = $this->getOption('relationColumn');

		$options = array('' => 'Choose option');
		foreach($targetEntity->findAll() as $item){
			if(!$item->$relCol){
				// throw new exception
			} else {
				$options[$item->id] = $item->$relCol;
			}
		}
		
		$this->setAttribute('required', true);

		$this->setValueOptions($options);
	}

	public function treatValueBeforeSave()
	{
		$targetEntity = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->getRepository($this->getOption('targetEntity'));
		return $targetEntity->find($this->getValue());
	}

	public function getListedValue($row)
	{
		return $row->{$this->getName()}->{$this->getOption('relationColumn')};
	}

	public function getOrderTable()
	{
		return $this->getOption('relationColumn');
	}

	public function getOrderColumn()
	{
		return $this->getName();
	}

	public function setServiceLocator(ServiceLocatorInterface $sl)
	{
		$this->serviceLocator = $sl;
	}

	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}

}
