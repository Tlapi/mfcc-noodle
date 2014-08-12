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
            
            if(get_class($element)=='Zend\Form\Element\Date' || get_class($element)=='Zend\Form\Element\DateTime'){
                //$form->getInputFilter()->get($element->getName())->getValidatorChain();
                $options=$element->getOptions();
                $regex = "[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}";
                if(isset($options['regex-format'])) {
                    $regex = $options['regex-format'];
                }
                else if(isset($options['format']))
                {   $regex = str_replace('.','\.',$options['format']);
                    $regex = str_replace('Y','[0-9]{4}',$regex);
                    $regex = str_replace(array('m','d','H','i','s'),'[0-9]{1,2}',$regex);
                }
                $va = new \Zend\Validator\Regex(array('pattern' => '/^'.$regex.'/'));
                $vc = new \Zend\Validator\ValidatorChain();
                $vc->attach($va);
                $form->getInputFilter()->get($element->getName())->setValidatorChain($vc);
            }

    		// add placehoder if present
    		if($element->getOption('placeholder')){
    			$element->setAttribute('placeholder', $element->getOption('placeholder'));
    		}

    	}
    	$form->setOption('sheets', $sheets);

    	$form->setAttribute('enctype', 'multipart/form-data');

		return $form;
    }
}