<?php
namespace Noodle\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormDateTime extends AbstractHelper implements ServiceLocatorAwareInterface {

	private $serviceLocator;

	public function render(ElementInterface $element) {

        $format = 'Y-m-d h:m:s';
        $options = $element->getOptions();
        if(isset($options["format"])) {
            $format = $options["format"];
        }
        
        $val = $element->getValue();
        if(!is_object($val)) 
        {   try {
                $val=new \DateTime($val);
            }
            catch(\Exception $ex) {
                $print = $val;
            }
            if(!isset($print)) $print = $val->format($format);
        }
        else $print = $val->format($format);
		return   '<input type="datetime" value="'.$print.'" required="required" name="'.$element->getName().'">
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