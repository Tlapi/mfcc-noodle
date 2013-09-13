<?php

namespace Noodle\Form\Element;

use Zend\Form\Element\Text;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrderId extends Text implements ServiceLocatorAwareInterface
{

	protected $serviceLocator;

	public function init()
	{
		// Here, we have $this->serviceLocator !!
	}

	public function prepare()
	{
		//var_dump($this->getOptions());

	}

	public function treatValueBeforeSave()
	{
		//$targetEntity = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->getRepository($this->getOption('targetEntity'));
		//return $targetEntity->find($this->getValue());
	}

	public function getListedValue($row)
	{
		return '<span class="drag" data-curid="'.$row->id.'" data-curindex="'.($this->getValue()?$this->getValue():$row->id).'">M</span>';
	}

	public function getOrderTable()
	{
		//return $this->getOption('relationColumn');
	}

	public function getOrderColumn()
	{
		//return $this->getName();
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
