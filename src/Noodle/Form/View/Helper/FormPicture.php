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
			$thumbnailer = $this->getServiceLocator()->getServiceLocator()->get('thumbnailerService');
			$basePath = $this->getServiceLocator()->get('basePath');

			$file = $fileBank->getFileById($element->getValue());

			return '<div class="form_picture_container media">
						<a class="pull-left form_picture" href="#">
							<img src="'.$basePath->__invoke().'/../'.$thumbnailer->getThumbnailUrl($file, 100, 100, true).'" alt="" />
							<input type="hidden" name="'.$element->getName().'" value="'.$element->getValue().'" />
						</a>
						<div class="media-body">
							<h4 class="media-heading">'.$file->getName().'</h4>
							<a href="#" class="remove">Remove picture</a>
							<input id="noodle_fileupload" type="file" name="files[]" data-url="'.$this->getServiceLocator()->get('url')->__invoke('noodle/filesystem/upload').'" multiple style="display: none">
							<div class="progress" style="width: 300px; display:none;">
								<div class="bar bar-success" style="width: 0%;"></div>
							</div>
						</div>
					</div>';
		} else {
			return '<div class="form_picture_container media">
						<a class="pull-left form_picture" href="#" style="display: none">
							<img src="" alt="" />
							<input type="hidden" name="'.$element->getName().'" value="" />
						</a>
						<div class="media-body">
							<h4 class="media-heading"></h4>
							<a href="#" class="remove" style="display: none">Remove picture</a>
							<input id="noodle_fileupload" type="file" name="files[]" data-url="'.$this->getServiceLocator()->get('url')->__invoke('noodle/filesystem/upload').'" multiple>
							<div class="progress" style="width: 300px; display:none;">
								<div class="bar bar-success" style="width: 0%;"></div>
							</div>
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