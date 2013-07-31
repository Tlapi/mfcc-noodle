<?php
// ./module/Application/src/Application/View/Helper/UserIcon.php
namespace Noodle\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleList extends AbstractHelper implements ServiceLocatorAwareInterface
{

	private $tableModules;
	private $vendorModules;

	public function __invoke()
    {
    	if(!isset($this->tableModules))
    		$this->tableModules = $this->getServiceLocator()->getServiceLocator()->get('modulesService')->getModules();
    	if(!isset($this->vendorModules))
    		$this->vendorModules = $this->getServiceLocator()->getServiceLocator()->get('modulesService')->getVendorModules();

    	return $this;
    }

    public function getTableModules()
    {
    	return $this->tableModules;
    }

    public function getVendorModules()
    {
    	return $this->vendorModules;
    }

    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->serviceLocator = $serviceLocator;
    	return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
    	return $this->serviceLocator;
    }
}