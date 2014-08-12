<?php
namespace Noodle\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormDateTime extends AbstractHelper implements ServiceLocatorAwareInterface {

	private $serviceLocator;

	public function render(ElementInterface $element) {

        $format = 'd.m.Y h:i:s';
        $options = $element->getOptions();
        
        $attributes = $element->getAttributes();
        
        //var_dump($options);
        if(isset($options["format"])) {
            $format = $options["format"];
        }
        
        $val = $element->getValue();
        if(!is_object($val) && $val!="" && $val!=null) 
        {   try {
               $val=new \DateTime($val);
            }
            catch(\Exception $ex) {
                $print = $val;
            }
            if(!isset($print)) $print = $val->format($format);
        }
        elseif($val==null || $val=="")
        {   $print = "";
        }
        else
        {   $print = $val->format($format);}
        
        $attributes = $element->getAttributes();
        //var_dump($attributes);
        if(!$attributes['required']) unset($attributes['required']);
        
        $attrs = array();
        foreach($attributes as $attr=>$val)
        {   $attrs[]=$attr.'="'.$val.'"'; }
        $attrs = implode(' ',$attrs);
		return   '<div class="datetimepicker input-add-on"><input value="'.$print.'" '.$attrs.' name="'.$element->getName().'">
		<span class="add-on">
            <i class="fa fa-calendar icon-calendar" data-date-icon="icon-calendar" data-time-icon="icon-time"></i>
        </span></div>';
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