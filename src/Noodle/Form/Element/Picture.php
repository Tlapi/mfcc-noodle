<?php

namespace Noodle\Form\Element;

use Zend\Form\Element\Text;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Picture extends Text implements ServiceLocatorAwareInterface
{

	protected $serviceLocator;

	protected $attributes = array(
			'type' => 'picture',
	);

	public function init()
	{
		// Here, we have $this->serviceLocator !!
	}

	public function prepare()
	{

	}

	public function getListedValue($row)
	{
		$value = $row->{$this->getName()};

		$thumbnailer = $this->getServiceLocator()->get('thumbnailerService');
		$basePath = $this->getServiceLocator()->get('viewhelpermanager')->get('basePath');

		if($value){
			$fileBank = $this->getServiceLocator()->get('FileBank');
			if($basePath->__invoke())
			    return '<img src="'.$basePath->__invoke().'/../'.$thumbnailer->getThumbnailUrl($fileBank->getFileById($value), 50, 50, true).'" alt="" />';
            else
			    return '<img src="'.str_replace('www_root', '', $thumbnailer->getThumbnailUrl($fileBank->getFileById($value), 50, 50, true)).'" alt="" />';
		} else {
			return null;
		}
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
