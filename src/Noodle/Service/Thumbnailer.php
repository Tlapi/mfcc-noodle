<?php
namespace Noodle\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Thumbnailer implements ServiceLocatorAwareInterface
{

	protected $serviceLocator;

	public function __construct()
	{
		// construct
	}

	/**
	 * Get image thumbnail
	 */
	public function getThumbnailUrl(\FileBank\Entity\File $file, $maxWidth = null, $maxHeight = null, $crop = false) {

		$name = $this->buildCacheName($file, $maxWidth, $maxHeight, $crop);

		if(!file_exists($name)){

			// TODO check if file is image
			$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
			$thumb = $thumbnailer->create($file->getAbsolutePath(), $options = array());

			if($crop){
				$thumb->adaptiveResize($maxWidth, $maxHeight);
				$thumb->cropFromCenter($maxWidth, $maxHeight);
			} else {
				$thumb->resize($maxWidth, $maxHeight);
			}

			$thumb->save($name);

		}

		return $name;
	}

	/**
	 * Build and get image cache name
	 * @param \FileBank\Entity\File $file
	 * @param string $maxWidth
	 * @param string $maxHeight
	 * @param string $crop
	 */
	public function buildCacheName(\FileBank\Entity\File $file, $maxWidth = null, $maxHeight = null, $crop = false) {
		$config = $this->getServiceLocator()->get('config');
		$name = $config['noodle']['cache_folder'].'/'.$file->getId()."_".$maxWidth."_".$maxHeight;		

		if($crop){
			$name .= '_crop';
		}

		$name .= ".jpg";

		return $name;
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

}