<?php
namespace Noodle\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormPicture extends AbstractHelper implements ServiceLocatorAwareInterface {

	private $serviceLocator;

	public function render(ElementInterface $element) {

		if($element->getValue()){
			$fileBank = $this->getServiceLocator()->get('FileBank');

			/*
			$thumbnailer = $this->getServiceLocator()->getServiceLocator()->get('WebinoImageThumb');
			$thumb = $thumbnailer->create($fileBank->getFileById($element->getValue())->getAbsolutePath(), $options = array());
			$thumb->resize(100, 100);

			$thumb->save('public/_data/resized.jpg');
			*/

			return '<div class="form_picture_container">
						<div class="form_picture">
							<img src="'.$fileBank->getFileById($element->getValue())->getUrl().'" alt="" />
							#'.$element->getValue().'
							<a href="#" class="remove">Remove picture</a>
							<input type="hidden" name="'.$element->getName().'" value="'.$element->getValue().'" />
						</div>
						<input id="fileupload" type="file" name="files[]" data-url="'.$this->getServiceLocator()->get('url')->__invoke('filesystem/upload').'" multiple style="display: none">
						<div class="progress" style="width: 300px">
							<div class="bar bar-success" style="width: 0%;"></div>
						</div>
					</div>';
		} else {
			return '<div class="form_picture_container">
						<div class="form_picture" style="display: none">
							<img src="" alt="" />
							<a href="#" class="remove">Remove picture</a>
							<input type="hidden" name="'.$element->getName().'" value="" />
						</div>
						<input id="fileupload" type="file" name="files[]" data-url="'.$this->getServiceLocator()->get('url')->__invoke('filesystem/upload').'" multiple>
						<div class="progress" style="width: 300px">
							<div class="bar bar-success" style="width: 0%;"></div>
						</div>
					</div>';
		}

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