<?php
namespace Noodle\Form\View\Helper;

use Application\Form\Element;
use Zend\Form\View\Helper\FormElement as BaseFormElement;
use Zend\Form\ElementInterface;

class FormElement extends BaseFormElement
{

	public function render(ElementInterface $element)
	{
		$renderer = $this->getView();

		if (!method_exists($renderer, 'plugin')) {
			// Bail early if renderer is not pluggable
			return '';
		}

		if ($element instanceof Element\Picture) {
			$helper = $renderer->plugin('formPicture');
			return $helper($element);
		}

		return parent::render($element);
	}

}