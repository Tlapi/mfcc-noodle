<?php
namespace Noodle\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

//use Zend\Form\Annotation\AnnotationBuilder;

class ModulesService implements ServiceLocatorAwareInterface, EventManagerAwareInterface
{

	protected $serviceLocator;

	protected $events;

	private $vendorModules = array();

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function __construct()
	{
		// construct
	}

	public function getModules()
	{
		return $this->getEntityManager()->getRepository('Noodle\Entity\Module')->findAll();
	}

	public function getVendorModules()
	{
		if(!$this->vendorModules)
			$this->getEventManager()->trigger('vendorModules.load', $this);
		return $this->vendorModules;
	}

	public function getModule($name)
	{
		return $this->getEntityManager()->getRepository('Noodle\Entity\Module')->findBy(array('entity' => $name));
	}

	public function addVendorModule($module)
	{
		$this->vendorModules[] = $module;
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

	public function setEventManager(EventManagerInterface $events)
	{
		$this->events = $events;
		return $this;
	}

	public function getEventManager()
	{
		if (!$this->events) {
			$this->setEventManager(new EventManager(__CLASS__));
		}
		return $this->events;
	}

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}

}