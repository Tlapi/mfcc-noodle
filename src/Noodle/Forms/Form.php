<?php
namespace Noodle\Forms;

//use Zend\Form\Element;
use Zend\Form\Factory;

class Form extends \Zend\Form\Factory
{
    protected $sm;

    public function setServiceLocator($sm)
    {
        $this->sm = $sm;
    }

    /**
     * Override form creation
     */
    public function createForm($formSpec)
    {
    	$factory = new Factory;
    	$form = $factory->createForm($formSpec);

    	// Handle custom Noodle datatypes and annotations
    	$sheets = array();
    	foreach($form->getElements() as $element){
    		// remove element if element is sheet
    		if($element->getOption('sheetType')=='cyclic'){
    			$form->remove($element->getName());
    			$sheets[$element->getName()] = $element;
    			continue;
    		}

    		// prepare element
    		if(method_exists($element, 'prepare')){
    			$element->setServiceLocator($this->sm);
    			$element->prepare();
    		}

    		// add placehoder if present
    		if($element->getOption('placeholder')){
    			$element->setAttribute('placeholder', $element->getOption('placeholder'));
    		}

    	}
    	$form->setOptions(array('sheets' => $sheets));

    	$form->setAttribute('enctype', 'multipart/form-data');

		return $form;
    }
}