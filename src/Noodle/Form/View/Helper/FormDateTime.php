<?php
namespace Noodle\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormDateTime extends AbstractHelper implements ServiceLocatorAwareInterface {

	private $serviceLocator;

	public function render(ElementInterface $element) {

		return   '<input type="datetime" value="'.$element->getValue()->format('Y-M-D h:m:s').'" required="required" name="'.$element->getName().'">
                  ';
    }

	public function __invoke(ElementInterface $element = null) {
		return $this->render($element);
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